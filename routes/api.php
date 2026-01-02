<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SensorDynamicController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// =============================================================
//      JALUR KHUSUS IoT (INTEGRASI GEMA SAWIT)
// =============================================================

// 1. Webhook Penerima Data (Dari Alat)
//    Method: POST, Function: store()
Route::post('/sensors/data', [SensorDynamicController::class, 'store']);
Route::post('/sensors/clear', [App\Http\Controllers\SensorDynamicController::class, 'clearData']);

Route::prefix('sensors')->group(function () {
    
    // 2. API Ringkasan Semua Node (Untuk Grid & Map)
    //    Method: GET, Function: summary() -> return ['nodes' => ...]
    Route::get('/summary', [SensorDynamicController::class, 'summary']); 

    // 3. API Detail Data (Untuk Tabel History)
    //    Method: GET, Function: getData() -> return ['data' => ...]
    Route::get('/get', [SensorDynamicController::class, 'getData']);

    // 4. API Grafik History (Untuk Chart.js)
    //    Method: GET, Function: history() -> return ['grafik' => ...]
    Route::get('/history', [SensorDynamicController::class, 'history']);

    // 5. API List Sensor (Untuk Dropdown Metadata)
    //    Karena di controller tidak ada function 'list', kita pakai Closure di sini saja
    //    supaya tidak perlu mengotak-atik controller.
    Route::get('/list', function () {
        return response()->json([
            'status'  => 'success',
            'sensors' => DB::table('sensor_list')->orderBy('sensor_id')->get(),
        ]);
    });
});