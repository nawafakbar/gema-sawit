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
    // POST /api/sensors/data
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sensor_id'       => 'required|string',
            'desibel'         => 'nullable|numeric',
            'decibel'         => 'nullable|numeric',
            'battery_percent' => 'required|integer',
            'timestamp'       => 'required|date',
        ]);

        $sensorId  = $validated['sensor_id'];

        // OPSI A: TOLAK DATA RUSAK (Disarankan)
        if (!str_starts_with($sensorIdRaw, 'ESP32')) {
            return response()->json([
                'status'  => 'error', 
                'message' => 'Invalid Sensor ID Format (Corrupted Data)'
            ], 422);
        }

        $tableName = strtolower($sensorId) . '_data';

        // Normalisasi: simpan ke kolom 'decibel' di DB
        $decibel = $validated['desibel'] ?? $validated['decibel'] ?? null;
        if ($decibel === null) {
            return response()->json([
                'status'  => 'error',
                'message' => 'desibel/decibel is required',
            ], 422);
        }

        // Daftar sensor
        if (!DB::table('sensor_list')->where('sensor_id', $sensorId)->exists()) {
            DB::table('sensor_list')->insert([
                'sensor_id'  => $sensorId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Tabel dinamis
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->float('decibel');
                $table->integer('battery_percent');
                $table->timestamp('timestamp');
                $table->timestamps();
            });
        }

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

        // Kirim notifikasi jika decibel > 40 (tanpa lat/lng/alias)
        if ($decibel > 70) {
            $message = "🚨 Aktivitas mencurigakan!\n"
                    . "Sensor: {$sensorId}\n"
                    . "Decibel: {$decibel}\n"
                    . "Waktu: " . Carbon::parse($validated['timestamp'])->format('d-m-Y H:i:s') . "\n"
                    . "Baterai: {$validated['battery_percent']}%";

            TelegramHelper::sendMessage($message);
        }

        return response()->json([
            'status'  => 'success',
            'message' => "Data inserted into {$tableName}",
        ]);
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
}