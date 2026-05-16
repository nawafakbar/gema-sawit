<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Helpers\TelegramHelper;
use Carbon\Carbon;

class TDOAService
{
    const DECIBEL_THRESHOLD      = 55;
    const TIME_WINDOW_SECONDS    = 300; // 5 menit
    const MAX_SENSOR_DIFF_SECONDS = 10; // max selisih antar sensor

    const SENSOR_POSITIONS = [
        'ESP32_A' => ['latitude' => -6.302000, 'longitude' => 106.652000],
        'ESP32_B' => ['latitude' => -6.301750, 'longitude' => 106.652480],
        'ESP32_C' => ['latitude' => -6.302350, 'longitude' => 106.652530],
        'ESP32_D' => ['latitude' => -6.302280, 'longitude' => 106.651970],
    ];

    public static function tryCalculate(string $triggerSensorId): void
    {
        $sensorPositions = self::SENSOR_POSITIONS;

        if (count($sensorPositions) < 3) return;

        $windowStart   = Carbon::now()->subSeconds(self::TIME_WINDOW_SECONDS);
        $latestRecords = [];

        foreach ($sensorPositions as $sensorId => $position) {
            $tableName = strtolower($sensorId) . '_data';

            if (!Schema::hasTable($tableName)) continue;

            $latest = DB::table($tableName)
                ->where('decibel', '>', self::DECIBEL_THRESHOLD)
                ->orderBy('id', 'desc')
                ->first();

            if ($latest) {
                $latestRecords[$sensorId] = $latest;
            }
        }

        if (count($latestRecords) < count($sensorPositions)) return;

        // Validasi selisih received_at antar sensor maksimal 10 detik
        $tsValues = array_map(
            fn($r) => Carbon::parse($r->received_at)->getPreciseTimestamp(6) / 1e6,
            $latestRecords
        );

        $maxTs = max($tsValues);
        $minTs = min($tsValues);

        if (($maxTs - $minTs) > self::MAX_SENSOR_DIFF_SECONDS) {
            return; // Bukan kejadian yang sama
        }

        // ✅ Gunakan received_at dengan presisi microsecond sebagai timestamp TDOA
        $timestamps = [];
        foreach ($latestRecords as $sensorId => $record) {
            $timestamps[$sensorId] = Carbon::parse($record->received_at)->getPreciseTimestamp(6) / 1e6;
        }

        $sensorIds = array_keys($latestRecords);
        $originId  = $sensorIds[0];
        $latOrigin = $sensorPositions[$originId]['latitude'];
        $lngOrigin = $sensorPositions[$originId]['longitude'];

        $sensorCoords = [];
        $timesInOrder = [];

        foreach ($sensorIds as $sensorId) {
            $pos            = $sensorPositions[$sensorId];
            $coords         = TDOACalculator::latLngToMeters(
                $pos['latitude'],
                $pos['longitude'],
                $latOrigin,
                $lngOrigin
            );
            $sensorCoords[] = $coords;
            $timesInOrder[] = $timestamps[$sensorId];
        }

        $result = TDOACalculator::calculate($sensorCoords, $timesInOrder);

        $eventData = [
            'sensor_1'   => $sensorIds[0] ?? null,
            'sensor_2'   => $sensorIds[1] ?? null,
            'sensor_3'   => $sensorIds[2] ?? null,
            'sensor_4'   => $sensorIds[3] ?? null,
            't1'         => $latestRecords[$sensorIds[0]]->received_at ?? null,
            't2'         => $latestRecords[$sensorIds[1]]->received_at ?? null,
            't3'         => $latestRecords[$sensorIds[2]]->received_at ?? null,
            't4'         => $latestRecords[$sensorIds[3]]->received_at ?? null,
            'decibel_1'  => $latestRecords[$sensorIds[0]]->decibel ?? null,
            'decibel_2'  => $latestRecords[$sensorIds[1]]->decibel ?? null,
            'decibel_3'  => $latestRecords[$sensorIds[2]]->decibel ?? null,
            'decibel_4'  => $latestRecords[$sensorIds[3]]->decibel ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($result) {
            $latLng = TDOACalculator::metersToLatLng(
                $result['x'],
                $result['y'],
                $latOrigin,
                $lngOrigin
            );

            $eventData['estimated_latitude']  = $latLng['latitude'];
            $eventData['estimated_longitude'] = $latLng['longitude'];
            $eventData['status']              = 'success';
            $eventData['notes']               = null;

            DB::table('tdoa_events')->insert($eventData);

            self::sendTelegramNotification($latLng, $latestRecords, $sensorIds);
        } else {
            $eventData['estimated_latitude']  = null;
            $eventData['estimated_longitude'] = null;
            $eventData['status']              = 'failed';
            $eventData['notes']               = 'Kalkulasi TDOA gagal (singular matrix atau data tidak cukup)';

            DB::table('tdoa_events')->insert($eventData);
        }
    }

    private static function sendTelegramNotification(array $latLng, array $latestRecords, array $sensorIds): void
    {
        $lat = number_format($latLng['latitude'], 6);
        $lng = number_format($latLng['longitude'], 6);

        $decibelLines = '';
        foreach ($sensorIds as $sensorId) {
            $db            = $latestRecords[$sensorId]->decibel ?? '-';
            $t             = isset($latestRecords[$sensorId]->received_at)
                ? Carbon::parse($latestRecords[$sensorId]->received_at)->format('H:i:s.u')
                : '-';
            $decibelLines .= "  • {$sensorId}: {$db} dB (terdeteksi {$t})\n";
        }

        $message = "📍 *Estimasi Lokasi Sumber Suara*\n"
            . "━━━━━━━━━━━━━━━━━━━━\n"
            . "🌐 Koordinat: `{$lat}, {$lng}`\n"
            . "🗺 Google Maps: https://maps.google.com/?q={$lat},{$lng}\n"
            . "━━━━━━━━━━━━━━━━━━━━\n"
            . "📡 Data Sensor:\n"
            . $decibelLines
            . "━━━━━━━━━━━━━━━━━━━━\n"
            . "🕐 Dihitung pada: " . Carbon::now()->format('d-m-Y H:i:s');

        TelegramHelper::sendMessage($message);
    }
}