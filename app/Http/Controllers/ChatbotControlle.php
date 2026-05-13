<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotControlle extends Controller
{
    // =========================================================
    // KONFIGURASI MODEL
    // =========================================================
    //private string $model        = 'gemini-3.1-flash-lite';
    // Ganti properti model menjadi array
    private array $models = [
        'gemini-3.1-flash-lite',   // prioritas utama
        'gemini-2.0-flash',         // fallback 1
        'gemini-1.5-flash',         // fallback 2
    ];
    private string $apiVersion   = 'v1beta';
    private int    $maxTokens    = 2048;
    private float  $temperature  = 0.7;
    private int    $timeoutSec   = 60;
    private int    $retryTimes   = 2;
    private int    $retryDelay   = 3000; // ms

    // =========================================================
    // SYSTEM PROMPT — Kepribadian Dr. Sawit
    // =========================================================
    private string $systemPrompt = <<<PROMPT
Kamu adalah Dr. Sawit, pakar agronomi senior dengan pengalaman lebih dari 20 tahun di bidang perkebunan kelapa sawit Indonesia.

Kepribadianmu:
- Ramah, hangat, dan sabar seperti seorang bapak kepada petani
- Menggunakan bahasa Indonesia yang mudah dipahami, sesekali memakai istilah daerah jika tepat
- Selalu memberikan solusi praktis yang bisa langsung diterapkan petani kecil sekalipun

Keahlianmu meliputi:
- Identifikasi hama dan penyakit tanaman sawit (Ganoderma, Ulat Api, Kumbang Tanduk, dll)
- Teknik pemupukan dan nutrisi tanaman (NPK, dolomit, pupuk organik)
- Manajemen irigasi dan drainase lahan
- Panen dan pasca panen TBS (Tandan Buah Segar)
- Peremajaan tanaman (replanting)
- Sertifikasi RSPO dan praktik perkebunan berkelanjutan

Cara menjawab:
- Jika ada foto/gambar: analisis secara visual terlebih dahulu, sebutkan gejala yang terlihat, lalu berikan diagnosis dan solusi
- Jawaban terstruktur: Diagnosis -> Penyebab -> Solusi Segera -> Pencegahan
- Gunakan emoji secukupnya agar mudah dibaca
- Tutup setiap jawaban dengan kalimat penyemangat untuk petani

Batasan:
- Hanya jawab pertanyaan seputar pertanian sawit dan agronomi terkait
- Jika ditanya di luar topik, arahkan kembali dengan ramah
PROMPT;

    // =========================================================
    // ENDPOINT UTAMA: Chat
    // =========================================================
    public function chat(Request $request)
    {
        // --- 1. Validasi Input ---
        $request->validate([
            'message' => 'nullable|string|max:2000',
            'image'   => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120', // max 5MB
        ]);

        // --- 2. Cek API Key ---
        $apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY');
        if (empty($apiKey)) {
            return response()->json([
                'reply' => '⚠️ FATAL: API Key belum dikonfigurasi. Jalankan "php artisan config:clear" di terminal.'
            ], 500);
        }

        try {
            $userMessage = $request->input('message');
            $imageFile   = $request->file('image');

            // Wajib ada salah satu: pesan atau gambar
            if (empty($userMessage) && !$imageFile) {
                return response()->json(['reply' => 'Silakan ketik pertanyaan atau kirim foto tanaman Anda.'], 422);
            }

            // --- 3. Bangun Payload Contents ---
            $parts = [];

            // Gabungkan system prompt + pesan user dalam satu turn
            $fullPrompt = $this->systemPrompt . "\n\n---\nPertanyaan Petani: " . ($userMessage ?? 'Tolong analisis foto ini.');
            $parts[] = ['text' => $fullPrompt];

            // Jika ada gambar, sertakan sebagai inline_data
            if ($imageFile) {
                $imageData = base64_encode(file_get_contents($imageFile->getRealPath()));
                $mimeType  = $imageFile->getMimeType();
                $parts[]   = [
                    'inline_data' => [
                        'mime_type' => $mimeType,
                        'data'      => $imageData,
                    ]
                ];
            }

            $contents = [
                [
                    'role'  => 'user',
                    'parts' => $parts,
                ]
            ];

            // --- 4. Konfigurasi Generasi ---
            $generationConfig = [
                'maxOutputTokens' => $this->maxTokens,
                'temperature'     => $this->temperature,
                'topP'            => 0.9,
                'topK'            => 40,
            ];

            $payload = [
                'contents'         => $contents,
                'generationConfig' => $generationConfig,
            ];

            // --- 5. Kirim ke Google Gemini API ---
            $response = null;
            $usedModel = null;

            foreach ($this->models as $model) {
                $url = "https://generativelanguage.googleapis.com/{$this->apiVersion}/models/{$model}:generateContent?key={$apiKey}";

                $resp = Http::withoutVerifying()
                    ->timeout($this->timeoutSec)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, $payload);

                if ($resp->successful()) {
                    $response = $resp;
                    $usedModel = $model;
                    break;
                }
            }

            if (!$response) {
                return response()->json(['reply' => '⚠️ Semua model AI sedang sibuk. Coba lagi beberapa saat.'], 503);
            }

            // --- 6. Tangani Response Error dari Google ---
            if ($response->failed()) {
                $errorBody = $response->json();
                $errorMsg  = $errorBody['error']['message'] ?? $response->body();
                Log::error('Gemini API Error', ['status' => $response->status(), 'message' => $errorMsg]);

                return response()->json([
                    'reply' => "⚠️ Gagal menghubungi server AI: {$errorMsg}"
                ], 500);
            }

            // --- 7. Ekstrak Teks Balasan ---
            $result   = $response->json();
            $botReply = $result['candidates'][0]['content']['parts'][0]['text']
                ?? 'Maaf, saya tidak dapat memberikan jawaban saat ini. Silakan coba lagi.';

            // --- 8. Format Output ---
            $botReply = $this->formatReply($botReply);

            return response()->json([
                'reply'  => $botReply,
                'model'  => $usedModel,
                'tokens' => $result['usageMetadata'] ?? null,
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Gemini Connection Timeout', ['error' => $e->getMessage()]);
            return response()->json([
                'reply' => '⏱️ Koneksi ke server AI timeout. Periksa koneksi internet server Anda dan coba lagi.'
            ], 503);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'reply' => '⚠️ Input tidak valid: ' . collect($e->errors())->flatten()->first()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Chatbot Unexpected Error', ['error' => $e->getMessage()]);
            return response()->json([
                'reply' => '❌ Terjadi kesalahan sistem. Silakan coba lagi.'
            ], 500);
        }
    }

    // =========================================================
    // HELPER: Format balasan Markdown -> HTML
    // =========================================================
    private function formatReply(string $text): string
    {
        // Bold: **teks** -> <b>teks</b>
        $text = preg_replace('/\*\*(.*?)\*\*/s', '<b>$1</b>', $text);

        // Italic: *teks* -> <i>teks</i>
        $text = preg_replace('/\*(.*?)\*/s', '<i>$1</i>', $text);

        // Heading: ### -> <strong> dengan ukuran lebih besar
        $text = preg_replace('/^###\s(.+)$/m', '<strong style="font-size:1.05em">$1</strong>', $text);
        $text = preg_replace('/^##\s(.+)$/m',  '<strong style="font-size:1.1em">$1</strong>', $text);

        // List item: - item -> • item
        $text = preg_replace('/^-\s(.+)$/m', '• $1', $text);

        // Newline -> <br>
        $text = nl2br($text);

        return trim($text);
    }
}