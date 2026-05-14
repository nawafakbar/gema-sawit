<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * McpService
 * Menghubungkan ke MCP MySQL (Lark AnyCross) dan menjalankan SQL query.
 *
 * Tabel yang digunakan (SmartPalm):
 *   - users       : data akun & profil petani
 *   - harvests    : data panen TBS
 *   - schedules   : jadwal pemeliharaan
 *   - transactions: data keuangan / transaksi
 *   - guides      : panduan / buku saku GAP & ISPO
 *
 * Cara pakai:
 *   $mcp = new McpService();
 *   $result = $mcp->query("SELECT * FROM harvests WHERE user_id = 1");
 */
class McpService
{
    private string $mcpUrl;
    private int    $timeout = 15;

    public function __construct()
    {
        // Simpan URL MCP (dengan API key) di .env sebagai MCP_MYSQL_URL
        // Contoh: MCP_MYSQL_URL=https://anycross.larksuite.com/mcp/MySQL/stream?key=xxxxx
        $this->mcpUrl = config('services.mcp.mysql_url') ?? env('MCP_MYSQL_URL', '');
    }

    // =========================================================
    // PUBLIC: Jalankan SQL query via MCP
    // =========================================================
    /**
     * @param  string $sql  Query SQL yang ingin dijalankan
     * @return array        ['success' => bool, 'data' => array|string]
     */
    public function query(string $sql): array
    {
        if (empty($this->mcpUrl)) {
            Log::warning('McpService: MCP_MYSQL_URL belum dikonfigurasi.');
            return ['success' => false, 'data' => 'MCP URL tidak dikonfigurasi.'];
        }

        try {
            // Format request MCP (JSON-RPC style yang dipakai AnyCross)
            $payload = [
                'jsonrpc' => '2.0',
                'id'      => uniqid('mcp_'),
                'method'  => 'tools/call',
                'params'  => [
                    'name'      => 'custom_sql',
                    'arguments' => [
                        'sql' => $sql,
                    ],
                ],
            ];

            $response = Http::withoutVerifying()
                ->timeout($this->timeout)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ])
                ->post($this->mcpUrl, $payload);

            if ($response->failed()) {
                Log::error('McpService HTTP Error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return ['success' => false, 'data' => 'Gagal menghubungi MCP server.'];
            }

            $json = $response->json();

            // Ambil hasil dari response MCP
            $result = $json['result'] ?? null;
            if (!$result) {
                $errMsg = $json['error']['message'] ?? 'Response MCP tidak valid.';
                return ['success' => false, 'data' => $errMsg];
            }

            // AnyCross mengembalikan content berupa array of text blocks
            $content = $result['content'] ?? [];
            $text    = collect($content)
                ->where('type', 'text')
                ->pluck('text')
                ->implode("\n");

            // Coba parse sebagai JSON (tabel data), kalau gagal return raw text
            $parsed = json_decode($text, true);
            $data   = is_array($parsed) ? $parsed : $text;

            return ['success' => true, 'data' => $data];

        } catch (\Exception $e) {
            Log::error('McpService Exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'data' => 'Error: ' . $e->getMessage()];
        }
    }

    // =========================================================
    // PUBLIC: Helper — ambil profil user/petani dari tabel users
    // =========================================================
    public function getUserData(int $userId): array
    {
        return $this->query(
            "SELECT id, name, email, land_area, location, created_at
             FROM users WHERE id = {$userId} LIMIT 1"
        );
    }

    // =========================================================
    // PUBLIC: Helper — ambil riwayat panen dari tabel harvests
    // =========================================================
    public function getHarvests(int $userId, int $limit = 6): array
    {
        return $this->query(
            "SELECT * FROM harvests
             WHERE user_id = {$userId}
             ORDER BY harvest_date DESC LIMIT {$limit}"
        );
    }

    // =========================================================
    // PUBLIC: Helper — ambil jadwal pemeliharaan dari tabel schedules
    // =========================================================
    public function getSchedules(int $userId, int $limit = 5): array
    {
        return $this->query(
            "SELECT * FROM schedules
             WHERE user_id = {$userId}
             ORDER BY scheduled_date ASC LIMIT {$limit}"
        );
    }

    // =========================================================
    // PUBLIC: Helper — ambil data transaksi/keuangan
    // =========================================================
    public function getTransactions(int $userId, int $limit = 10): array
    {
        return $this->query(
            "SELECT * FROM transactions
             WHERE user_id = {$userId}
             ORDER BY transaction_date DESC LIMIT {$limit}"
        );
    }

    // =========================================================
    // PUBLIC: Helper — ambil panduan relevan dari tabel guides
    // (bisa dipakai untuk context tambahan, opsional)
    // =========================================================
    public function getGuides(string $keyword = '', int $limit = 3): array
    {
        if ($keyword) {
            $safe = addslashes($keyword);
            $sql  = "SELECT title, content FROM guides
                     WHERE title LIKE '%{$safe}%' OR content LIKE '%{$safe}%'
                     LIMIT {$limit}";
        } else {
            $sql = "SELECT title, content FROM guides LIMIT {$limit}";
        }
        return $this->query($sql);
    }

    // =========================================================
    // PUBLIC: Buat ringkasan konteks petani untuk dikirim ke AI
    // =========================================================
    /**
     * Menggabungkan semua data SmartPalm menjadi teks konteks untuk system prompt.
     * Dipanggil dari ChatbotController sebelum query ke Gemini.
     *
     * @param  int    $userId   ID user yang sedang login
     * @param  string $keyword  Kata kunci dari pertanyaan user (untuk filter guides)
     */
    public function buildKonteksPetani(int $userId, string $keyword = ''): string
    {
        $lines = [];

        // 1. Profil petani
        $user = $this->getUserData($userId);
        if ($user['success'] && !empty($user['data'])) {
            $lines[] = "=== PROFIL PETANI ===";
            $lines[] = is_array($user['data'])
                ? $this->arrayToText($user['data'])
                : $user['data'];
        }

        // 2. Riwayat panen terbaru
        $harvests = $this->getHarvests($userId);
        if ($harvests['success'] && !empty($harvests['data'])) {
            $lines[] = "\n=== RIWAYAT PANEN (6 terakhir) ===";
            $lines[] = is_array($harvests['data'])
                ? $this->arrayToText($harvests['data'])
                : $harvests['data'];
        }

        // 3. Jadwal pemeliharaan mendatang
        $schedules = $this->getSchedules($userId);
        if ($schedules['success'] && !empty($schedules['data'])) {
            $lines[] = "\n=== JADWAL PEMELIHARAAN ===";
            $lines[] = is_array($schedules['data'])
                ? $this->arrayToText($schedules['data'])
                : $schedules['data'];
        }

        // 4. Data keuangan / transaksi
        $transactions = $this->getTransactions($userId);
        if ($transactions['success'] && !empty($transactions['data'])) {
            $lines[] = "\n=== DATA TRANSAKSI / KEUANGAN ===";
            $lines[] = is_array($transactions['data'])
                ? $this->arrayToText($transactions['data'])
                : $transactions['data'];
        }

        // 5. Panduan relevan (jika ada keyword dari pertanyaan)
        if (!empty($keyword)) {
            $guides = $this->getGuides($keyword);
            if ($guides['success'] && !empty($guides['data'])) {
                $lines[] = "\n=== PANDUAN RELEVAN ===";
                $lines[] = is_array($guides['data'])
                    ? $this->arrayToText($guides['data'])
                    : $guides['data'];
            }
        }

        return implode("\n", $lines);
    }

    // =========================================================
    // PRIVATE: Konversi array data menjadi teks mudah dibaca AI
    // =========================================================
    private function arrayToText(array $data): string
    {
        // Jika array of rows (tabel)
        if (isset($data[0]) && is_array($data[0])) {
            $rows = [];
            foreach ($data as $row) {
                $cols = [];
                foreach ($row as $key => $val) {
                    $cols[] = "{$key}: {$val}";
                }
                $rows[] = implode(' | ', $cols);
            }
            return implode("\n", $rows);
        }

        // Kalau single row
        $cols = [];
        foreach ($data as $key => $val) {
            $cols[] = "{$key}: {$val}";
        }
        return implode(' | ', $cols);
    }
}