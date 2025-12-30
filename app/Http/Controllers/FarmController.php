<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guide;
use App\Models\Schedule;
use App\Models\ActivityLog;
use App\Models\Harvest;
use Barryvdh\DomPDF\Facade\Pdf;

class FarmController extends Controller
{
    // --- 1. DASHBOARD ---

    public function dashboard(Request $request) {
        // --- 1. DATA KARTU STATISTIK (Sama seperti sebelumnya) ---
        $jadwal_pending = Schedule::where('status', 'pending')->count();
        $last_activity = ActivityLog::latest('date')->first();
        $status_lahan = $last_activity && $last_activity->date >= now()->subDays(7) ? 'Terawat' : 'Perlu Perhatian';

        // --- 2. DATA GRAFIK PANEN (Tahunan) ---
        // Ambil tahun dari dropdown (default tahun ini)
        $selectedYear = $request->input('year', date('Y'));
        
        // Ambil list tahun yang tersedia di database untuk dropdown
        $availableYears = Harvest::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Query Data: Jumlahkan berat per bulan untuk tahun yang dipilih
        $monthlyData = Harvest::selectRaw('MONTH(date) as month, SUM(weight_kg) as total')
            ->whereYear('date', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Normalisasi Data (Isi bulan kosong dengan 0) agar grafik tidak bolong
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData[$i] ?? 0;
        }
        $total_panen_tahun_ini = array_sum($chartData); // Total setahun

        return view('pages.dashboard', compact(
            'jadwal_pending', 
            'status_lahan', 
            'chartData', 
            'selectedYear', 
            'availableYears', 
            'total_panen_tahun_ini'
        ));
    }

    // --- 2. BUKU SAKU (GUIDES) ---
    public function guides() {
        $guides = Guide::all(); // Nanti bisa diisi via seeder/database manual
        return view('pages.guides', compact('guides'));
    }

    // Hapus Panduan
    public function destroyGuide($id) {
        \App\Models\Guide::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Panduan berhasil dihapus.');
    }

    // --- 3. JADWAL (SCHEDULES) ---
    public function schedules() {
        // Urutkan jadwal: yang pending di atas, lalu berdasarkan tanggal
        $schedules = Schedule::orderBy('status', 'desc')->orderBy('date', 'asc')->get();
        return view('pages.schedules', compact('schedules'));
    }

    // Fitur: Tandai Selesai (Update Status)
    public function completeSchedule($id) {
        $schedule = Schedule::findOrFail($id);
        $schedule->update(['status' => 'done']);
        
        return redirect()->back()->with('success', 'Jadwal berhasil diselesaikan!');
    }

    // --- 4. LOGBOOK ISPO ---
    public function logbook() {
        $logs = ActivityLog::orderBy('date', 'desc')->get();
        return view('pages.logbook', compact('logs'));
    }

    // Fitur: Simpan Log Baru
    public function storeLog(Request $request) {
        $request->validate([
            'activity_type' => 'required',
            'material' => 'required',
            'dose' => 'required',
            'date' => 'required|date' // Tambahkan input tanggal hidden/auto
        ]);

        ActivityLog::create([
            'date' => $request->date ?? now(), // Default hari ini jika kosong
            'activity_type' => $request->activity_type,
            'material' => $request->material,
            'dose' => $request->dose
        ]);

        return redirect()->back()->with('success', 'Aktivitas berhasil dicatat!');
    }

    // Fitur: Hapus Log
    public function destroyLog($id) {
        ActivityLog::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    // --- 5. BUKU PANEN ---
    public function harvests() {
        $harvests = Harvest::orderBy('date', 'desc')->get();
        return view('pages.harvests', compact('harvests'));
    }

    // Fitur: Simpan Panen Baru
    public function storeHarvest(Request $request) {
        $request->validate([
            'date' => 'required|date',
            'block' => 'required',
            'weight_kg' => 'required|numeric'
        ]);

        Harvest::create($request->all());

        return redirect()->back()->with('success', 'Data panen disimpan!');
    }

    // Fitur: Hapus Panen
    public function destroyHarvest($id) {
        Harvest::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data panen dihapus.');
    }

    // Simpan Guide Baru
    public function storeGuide(Request $request) {
        Guide::create($request->validate([
            'title' => 'required',
            'category' => 'required',
            'content' => 'required'
        ]));
        return redirect()->back()->with('success', 'Panduan berhasil ditambahkan');
    }

    // Simpan Jadwal Baru
    public function storeSchedule(Request $request) {
        Schedule::create($request->validate([
            'activity' => 'required',
            'date' => 'required|date',
        ]));
        return redirect()->back()->with('success', 'Jadwal berhasil dibuat');
    }

    public function destroySchedule($id) {
        Schedule::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function exportPdf() {
        $logs = ActivityLog::orderBy('date', 'desc')->get();
        
        // Load view khusus PDF
        $pdf = Pdf::loadView('pdf.logbook', compact('logs'));
        
        // Download file
        return $pdf->download('laporan-ispo-'.date('Y-m-d').'.pdf');
    }

    // --- 6. KEUANGAN & ARUS KAS ---
    public function finance(Request $request) {
        // Filter Bulan & Tahun (Default bulan ini)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Ambil Data Transaksi sesuai filter
        $transactions = \App\Models\Transaction::whereMonth('date', $month)
                        ->whereYear('date', $year)
                        ->orderBy('date', 'desc')
                        ->get();

        // Hitung Ringkasan
        $total_income = $transactions->where('type', 'income')->sum('amount');
        $total_expense = $transactions->where('type', 'expense')->sum('amount');
        $saldo = $total_income - $total_expense;

        return view('pages.finance', compact('transactions', 'total_income', 'total_expense', 'saldo', 'month', 'year'));
    }

    public function storeTransaction(Request $request) {
        // Validasi
        $request->validate([
            'date' => 'required|date',
            'type' => 'required',
            'category' => 'required',
            'amount' => 'required|numeric',
        ]);

        \App\Models\Transaction::create($request->all());

        return redirect()->back()->with('success', 'Catatan keuangan tersimpan!');
    }

    public function destroyTransaction($id) {
        \App\Models\Transaction::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Transaksi dihapus.');
    }
}