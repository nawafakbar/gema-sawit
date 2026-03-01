<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use App\Helpers\TelegramHelper;
use Carbon\Carbon;

class SensorDynamicController extends Controller
{
    // =========================================================================
    // 1. KONFIGURASI KOORDINAT (GANTI DENGAN TITIK ASLI GOOGLE MAPS)
    // =========================================================================
    private $sensor_locations = [
        'ESP32_001' => ['lat' => -0.91234567,  'lng' => 100.35511111], 
        'ESP32_002' => ['lat' => -0.91244567,  'lng' => 100.35522222],
        'ESP32_003' => ['lat' => -0.91254567,  'lng' => 100.35533333],
        'ESP32_004' => ['lat' => -0.91264567,  'lng' => 100.35544444],
        'ESP32_005' => ['lat' => -0.91274567,  'lng' => 100.35555555],
    ];

    // =========================================================================
    // 2. FUNGSI UTAMA: MENERIMA DATA (STORE)
    // =========================================================================
    public function store(Request $request)
    {
        // Validasi input (Logika Lama)
        $validated = $request->validate([
            'sensor_id'       => 'required|string',
            'desibel'         => 'nullable|numeric',
            'decibel'         => 'nullable|numeric',
            'battery_percent' => 'required|integer',
            'timestamp'       => 'required|date',
        ]);

        $sensorId  = $validated['sensor_id'];

        // OPSI A: TOLAK DATA RUSAK (Logika Lama)
        if (!str_starts_with($sensorId, 'ESP32_')) {
            return response()->json([
                'status'  => 'error', 
                'message' => 'Invalid Sensor ID Format (Corrupted Data)'
            ], 422);
        }

        $tableName = strtolower($sensorId) . '_data';

        // Normalisasi: simpan ke kolom 'decibel' di DB (Logika Lama)
        $decibel = $validated['desibel'] ?? $validated['decibel'] ?? null;
        if ($decibel === null) {
            return response()->json([
                'status'  => 'error',
                'message' => 'desibel/decibel is required',
            ], 422);
        }

        // Daftar sensor (Logika Lama)
        if (!DB::table('sensor_list')->where('sensor_id', $sensorId)->exists()) {
            DB::table('sensor_list')->insert([
                'sensor_id'  => $sensorId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tabel dinamis (Logika Lama)
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->float('decibel');
                $table->integer('battery_percent');
                $table->timestamp('timestamp');
                $table->timestamps();
            });
        }

        // Insert Data (Logika Lama)
        try {
            DB::table($tableName)->insert([
                'decibel'         => $decibel,
                'battery_percent' => $validated['battery_percent'],
                'timestamp'       => $validated['timestamp'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to insert data: ' . $e->getMessage(),
            ], 500);
        }

        // --- BAGIAN INI DIMODIFIKASI UNTUK TDOA ---
        // Kirim notifikasi HANYA jika hasil kalkulasi TDOA valid (Single notif dihapus)
        $tdoaResult = null;
        if ($decibel > 55) {
            // Cari data sensor lain di menit yang sama
            $eventData = $this->gatherEventData($validated['timestamp']);

            // Syarat TDOA: Minimal 3 Sensor mendengar suara > 55dB
            if (count($eventData) >= 3) {
                // Hitung Posisi
                $tdoaResult = $this->calculatePosition($eventData);
                
                // Jika koordinat ketemu, kirim notifikasi TDOA
                if ($tdoaResult) {
                    $this->sendTdoaNotification($tdoaResult, $eventData);
                }
            }
        }
        // ------------------------------------------

        return response()->json([
            'status'  => 'success',
            'message' => "Data inserted into {$tableName}",
            'tdoa_analysis' => $tdoaResult ? 'Location Found' : 'Data Saved (Waiting for more sensors)'
        ]);
    }

    // =========================================================================
    // 3. FUNGSI PENDUKUNG LAMA (CLEAR, GET, SUMMARY, HISTORY)
    // =========================================================================

    // POST /api/sensors/clear
    public function clearData(Request $request)
    {
        $sensorId = $request->input('sensor_id');

        if (!$sensorId) {
            return response()->json(['status' => 'error', 'message' => 'Sensor ID required'], 422);
        }

        $tableName = strtolower($sensorId) . '_data';

        if (Schema::hasTable($tableName)) {
            // Truncate menghapus semua data & mereset ID ke 1
            DB::table($tableName)->truncate(); 
            
            return response()->json([
                'status' => 'success',
                'message' => "Data sensor {$sensorId} berhasil dikosongkan."
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Table not found'], 404);
    }

    // GET /api/sensors/get?sensor_id=ESP32_A
    public function getData(Request $request)
    {
        $sensorId = $request->query('sensor_id');
        if (!$sensorId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'sensor_id query parameter is required',
            ], 422);
        }

        $tableName = strtolower($sensorId) . '_data';

        if (!Schema::hasTable($tableName)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Table not found for sensor ' . $sensorId,
            ], 404);
        }

        $data = DB::table($tableName)
                 ->orderBy('timestamp', 'desc')
                 ->limit(50)
                 ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    // GET /api/sensors/summary
    public function summary()
    {
        $sensors = DB::table('sensor_list')->orderBy('sensor_id')->get();
        $nodes   = [];

        foreach ($sensors as $s) {
            $table = strtolower($s->sensor_id) . '_data';
            if (Schema::hasTable($table)) {
                $latest = DB::table($table)
                           ->orderBy('timestamp', 'desc')
                           ->first();

                if ($latest) {
                    $nodes[] = [
                        'node_id'            => $s->sensor_id,
                        'desibel'            => $latest->decibel,
                        'presentase_baterai' => $latest->battery_percent,
                        'created_at'         => $latest->timestamp,
                    ];
                } else {
                    $nodes[] = [
                        'node_id'            => $s->sensor_id,
                        'desibel'            => null,
                        'presentase_baterai' => null,
                        'created_at'         => null,
                    ];
                }
            } else {
                $nodes[] = [
                    'node_id'            => $s->sensor_id,
                    'desibel'            => null,
                    'presentase_baterai' => null,
                    'created_at'         => null,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'nodes'  => $nodes,
        ]);
    }

    // GET /api/sensors/history?sensor_id=ESP32_A&limit=30
    public function history(Request $request)
    {
        $sensorId = $request->query('sensor_id');
        $limit    = (int)($request->query('limit', 30));

        if (!$sensorId) {
            return response()->json([
                'status'  => 'error',
                'message' => 'sensor_id query parameter is required',
            ], 422);
        }

        $table = strtolower($sensorId) . '_data';
        if (!Schema::hasTable($table)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Table not found for sensor ' . $sensorId,
            ], 404);
        }

        $rows = DB::table($table)
                 ->orderBy('timestamp', 'desc')
                 ->limit($limit)
                 ->get();

        $grafik = $rows->map(function ($r) {
            return [
                'created_at'         => $r->timestamp,
                'desibel'            => $r->decibel,
                'presentase_baterai' => $r->battery_percent,
            ];
        });

        return response()->json([
            'status' => 'success',
            'grafik' => $grafik,
        ]);
    }

    // =========================================================================
    // 4. PRIVATE HELPER FUNCTIONS (UNTUK LOGIKA TDOA)
    // =========================================================================

    // Fungsi: Mengumpulkan Data dari Semua Tabel di Menit yang Sama
    private function gatherEventData($timestampInput)
    {
        $targetTime = Carbon::parse($timestampInput);
        $startTime = $targetTime->copy()->startOfMinute();
        $endTime   = $targetTime->copy()->endOfMinute();

        $activeSensors = [];
        $allSensors = array_keys($this->sensor_locations);

        foreach ($allSensors as $sensor) {
            $tableName = strtolower($sensor) . '_data';
            
            if (Schema::hasTable($tableName)) {
                $record = DB::table($tableName)
                    ->whereBetween('timestamp', [$startTime, $endTime])
                    ->where('decibel', '>', 55)
                    ->orderBy('decibel', 'desc')
                    ->first();

                if ($record) {
                    $activeSensors[] = [
                        'id'  => $sensor,
                        'lat' => $this->sensor_locations[$sensor]['lat'],
                        'lng' => $this->sensor_locations[$sensor]['lng'],
                        'db'  => $record->decibel
                    ];
                }
            }
        }
        return $activeSensors;
    }

    // Fungsi: Hitung Posisi (Weighted Centroid)
    private function calculatePosition($sensors)
    {
        $sumLatW = 0; $sumLngW = 0; $sumW = 0;

        foreach ($sensors as $s) {
            // Bobot pangkat 4 agar sensor terdekat (suara terkeras) sangat dominan
            $weight = pow($s['db'], 4); 
            
            $sumLatW += $s['lat'] * $weight;
            $sumLngW += $s['lng'] * $weight;
            $sumW    += $weight;
        }

        if ($sumW == 0) return null;

        return [
            'lat' => $sumLatW / $sumW,
            'lng' => $sumLngW / $sumW
        ];
    }

    // Fungsi: Kirim Notifikasi Telegram TDOA
    private function sendTdoaNotification($coord, $sensors)
    {
        $listSensor = "";
        foreach($sensors as $s) {
            $listSensor .= "- {$s['id']}: {$s['db']} dB\n";
        }

        // Link Google Maps
        $gmapsLink = "https://www.google.com/maps?q={$coord['lat']},{$coord['lng']}";

        $message = "🚨 <b>BAHAYA! AKTIVITAS MENCURIGAKAN (TDOA DETECTED)</b>\n\n"
                 . "Sistem mendeteksi suara keras dari " . count($sensors) . " sensor sekaligus.\n\n"
                 . "📍 <b>Lokasi Estimasi Sumber Suara:</b>\n"
                 . "<a href='{$gmapsLink}'>KLIK UNTUK BUKA PETA (NAVIGASI)</a>\n"
                 . "(Lat: {$coord['lat']}, Lng: {$coord['lng']})\n\n"
                 . "🔍 <b>Bukti Deteksi:</b>\n"
                 . $listSensor
                 . "\n<i>Segera luncurkan tim patroli ke titik tersebut!</i>";
        
        TelegramHelper::sendMessage($message);
    }
}