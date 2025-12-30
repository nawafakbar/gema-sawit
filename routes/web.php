<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\ChatbotControlle;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SensorDynamicController;

// 1. HALAMAN PUBLIK (Landing Page)
Route::get('/', function () {
    return view('landing');
})->name('home');

// 2. AUTHENTICATION (Hanya Login, Register Dibuang)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. AREA KHUSUS MEMBER (Harus Login)
Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    
    // Redirect /dashboard ke controller dashboard
    Route::get('/', [FarmController::class, 'dashboard'])->name('dashboard');

    Route::controller(FarmController::class)->group(function () {
        // Guides
        Route::get('/guides', 'guides');
        Route::post('/guides', 'storeGuide')->name('guides.store');
        Route::delete('/guides/{id}', 'destroyGuide')->name('guides.destroy');

        // Schedules
        Route::get('/schedules', 'schedules');
        Route::post('/schedules', 'storeSchedule')->name('schedules.store');
        Route::patch('/schedules/{id}/complete', 'completeSchedule')->name('schedules.complete');
        Route::delete('/schedules/{id}', 'destroySchedule')->name('schedules.destroy');

        // Logbook
        Route::get('/logbook', 'logbook');
        Route::post('/logbook', 'storeLog')->name('logbook.store');
        Route::delete('/logbook/{id}', 'destroyLog')->name('logbook.destroy');
        Route::get('/logbook/export', 'exportPdf')->name('logbook.export');

        // Harvests
        Route::get('/harvests', 'harvests');
        Route::post('/harvests', 'storeHarvest')->name('harvests.store');
        Route::delete('/harvests/{id}', 'destroyHarvest')->name('harvests.destroy');
        
        // Finance
        Route::get('/finance', 'finance');
        Route::post('/finance', 'storeTransaction')->name('finance.store');
        Route::delete('/finance/{id}', 'destroyTransaction')->name('finance.destroy');
    });

    // Chatbot AI
    Route::post('/chat-ai', [ChatbotControlle::class, 'chat'])->name('chat.ai');

    // Halaman Monitoring IoT
    Route::get('/monitoring', function () {
        return view('pages.monitoring');
    })->name('monitoring');
    
});

// Route::controller(FarmController::class)->group(function () {
//     // Halaman Utama
//     Route::get('/', 'dashboard');
//     Route::get('/guides', 'guides');
//     Route::post('/guides', 'storeGuide')->name('guides.store');

//     // Jadwal
//     Route::get('/schedules', 'schedules');
//     Route::patch('/schedules/{id}/complete', 'completeSchedule')->name('schedules.complete'); // Aksi tombol "Kerjakan"
//     Route::post('/schedules', 'storeSchedule')->name('schedules.store');
//     Route::delete('/schedules/{id}', 'destroySchedule')->name('schedules.destroy');
    
//     // Logbook
//     Route::get('/logbook', 'logbook');
//     Route::post('/logbook', 'storeLog')->name('logbook.store'); // Simpan
//     Route::delete('/logbook/{id}', 'destroyLog')->name('logbook.destroy'); // Hapus
    
//     // Panen
//     Route::get('/harvests', 'harvests');
//     Route::post('/harvests', 'storeHarvest')->name('harvests.store'); // Simpan
//     Route::delete('/harvests/{id}', 'destroyHarvest')->name('harvests.destroy'); // Hapus

//     //export pdf
//     Route::get('/logbook/export', 'exportPdf')->name('logbook.export');

//     //keuangan
//     Route::get('/finance', 'finance');
//     Route::post('/finance', 'storeTransaction')->name('finance.store');
//     Route::delete('/finance/{id}', 'destroyTransaction')->name('finance.destroy');

//     // Route untuk Chatbot AI
//     Route::post('/chat-ai', [ChatbotControlle::class, 'chat'])->name('chat.ai');
// });