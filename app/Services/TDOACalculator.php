<?php

namespace App\Services;

class TDOACalculator
{
    // Kecepatan suara (m/s)
    const SPEED_OF_SOUND = 343.0;

    /**
     * Konversi lat/lng ke koordinat meter relatif terhadap origin
     * 
     * @param float $lat
     * @param float $lng
     * @param float $latOrigin
     * @param float $lngOrigin
     * @return array [x, y] dalam meter
     */
    public static function latLngToMeters(float $lat, float $lng, float $latOrigin, float $lngOrigin): array
    {
        $x = ($lng - $lngOrigin) * 111320 * cos(deg2rad($latOrigin));
        $y = ($lat - $latOrigin) * 110540;

        return ['x' => $x, 'y' => $y];
    }

    /**
     * Konversi koordinat meter kembali ke lat/lng
     * 
     * @param float $x
     * @param float $y
     * @param float $latOrigin
     * @param float $lngOrigin
     * @return array [latitude, longitude]
     */
    public static function metersToLatLng(float $x, float $y, float $latOrigin, float $lngOrigin): array
    {
        $lat = $latOrigin + ($y / 110540);
        $lng = $lngOrigin + ($x / (111320 * cos(deg2rad($latOrigin))));

        return ['latitude' => $lat, 'longitude' => $lng];
    }

    /**
     * Hitung estimasi posisi sumber suara menggunakan TDOA
     * dengan metode Least Squares (iteratif)
     *
     * @param array $sensors  [ ['x'=>.., 'y'=>..], ... ] koordinat sensor dalam meter
     * @param array $times    [ t1, t2, t3, t4 ] timestamp dalam detik (float)
     * @return array ['x' => .., 'y' => ..] atau null jika gagal
     */
    public static function calculate(array $sensors, array $times): ?array
    {
        $n = count($sensors);

        if ($n < 3) {
            return null; // Minimal 3 sensor
        }

        // Gunakan sensor pertama sebagai referensi
        $refTime = $times[0];
        $refX    = $sensors[0]['x'];
        $refY    = $sensors[0]['y'];

        // Hitung TDOA (selisih waktu dalam detik) dan konversi ke selisih jarak (meter)
        $tdoa = [];
        for ($i = 1; $i < $n; $i++) {
            $deltaT  = $times[$i] - $refTime;
            $tdoa[]  = $deltaT * self::SPEED_OF_SOUND; // selisih jarak dalam meter
        }

        // Iterative Least Squares (Taylor Series Linearization)
        // Tebakan awal: rata-rata posisi semua sensor
        $x = array_sum(array_column($sensors, 'x')) / $n;
        $y = array_sum(array_column($sensors, 'y')) / $n;

        $maxIterations = 100;
        $tolerance     = 1e-6;

        for ($iter = 0; $iter < $maxIterations; $iter++) {
            $H = [];
            $b = [];

            $d0 = sqrt(($x - $refX) ** 2 + ($y - $refY) ** 2);
            if ($d0 < 1e-10) $d0 = 1e-10;

            for ($i = 1; $i < $n; $i++) {
                $si = $sensors[$i];
                $di = sqrt(($x - $si['x']) ** 2 + ($y - $si['y']) ** 2);
                if ($di < 1e-10) $di = 1e-10;

                // Jacobian row
                $H[] = [
                    ($x - $si['x']) / $di - ($x - $refX) / $d0,
                    ($y - $si['y']) / $di - ($y - $refY) / $d0,
                ];

                // Residual
                $b[] = $tdoa[$i - 1] - ($di - $d0);
            }

            // Hitung (H^T * H)^-1 * H^T * b  (Least Squares)
            $delta = self::leastSquares($H, $b);

            if ($delta === null) {
                return null;
            }

            $x += $delta[0];
            $y += $delta[1];

            // Konvergen?
            if (abs($delta[0]) < $tolerance && abs($delta[1]) < $tolerance) {
                break;
            }
        }

        return ['x' => $x, 'y' => $y];
    }

    /**
     * Solve sistem persamaan least squares: (H^T H)^-1 H^T b
     * Untuk matriks 2 kolom (2D problem)
     */
    private static function leastSquares(array $H, array $b): ?array
    {
        // H^T * H  (2x2 matrix)
        $HtH = [[0, 0], [0, 0]];
        foreach ($H as $row) {
            $HtH[0][0] += $row[0] * $row[0];
            $HtH[0][1] += $row[0] * $row[1];
            $HtH[1][0] += $row[1] * $row[0];
            $HtH[1][1] += $row[1] * $row[1];
        }

        // H^T * b  (2x1 vector)
        $Htb = [0, 0];
        foreach ($H as $i => $row) {
            $Htb[0] += $row[0] * $b[$i];
            $Htb[1] += $row[1] * $b[$i];
        }

        // Inverse 2x2: [[a,b],[c,d]]^-1 = 1/(ad-bc) * [[d,-b],[-c,a]]
        $det = $HtH[0][0] * $HtH[1][1] - $HtH[0][1] * $HtH[1][0];

        if (abs($det) < 1e-10) {
            return null; // Singular matrix, tidak bisa diselesaikan
        }

        $invDet = 1.0 / $det;
        $inv    = [
            [ $HtH[1][1] * $invDet, -$HtH[0][1] * $invDet],
            [-$HtH[1][0] * $invDet,  $HtH[0][0] * $invDet],
        ];

        return [
            $inv[0][0] * $Htb[0] + $inv[0][1] * $Htb[1],
            $inv[1][0] * $Htb[0] + $inv[1][1] * $Htb[1],
        ];
    }
}