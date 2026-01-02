@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="mb-8 flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Monitoring</h1>
            <p class="text-gray-500 mt-1">Pantau produktivitas dan kondisi lingkungan terkini.</p>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-sm text-gray-500">Tanggal Hari Ini</p>
            <p class="font-bold text-gray-800">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Status Lahan</p>
                <h3 class="text-xl font-medium {{ $status_lahan == 'Terawat' ? 'text-brand-600' : 'text-red-500' }} mt-1">
                    {{ $status_lahan }}
                </h3>
                <p class="text-xs text-gray-400 mt-1">Berdasarkan logbook 7 hari terakhir</p>
            </div>
            <div class="p-3 {{ $status_lahan == 'Terawat' ? 'bg-brand-50 text-brand-600' : 'bg-red-50 text-red-500' }} rounded-xl">
                <i class="ph-fill ph-heartbeat text-2xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Tugas Menunggu</p>
                <h3 class="text-xl font-bold text-gray-800 mt-1">{{ $jadwal_pending }} <span class="text-sm font-normal text-gray-500">Aktivitas</span></h3>
                <a href="{{ url('/dashboard/schedules') }}" class="text-xs text-brand-600 font-bold mt-1 hover:underline">Lihat Jadwal &rarr;</a>
            </div>
            <div class="p-3 bg-orange-50 text-orange-500 rounded-xl">
                <i class="ph-fill ph-clock text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Grafik Hasil Panen</h3>
                <p class="text-gray-500 text-sm">Total Produksi: <span class="font-bold text-brand-600">{{ number_format($total_panen_tahun_ini) }} Kg</span> (Tahun {{ $selectedYear }})</p>
            </div>
            
            <form action="{{ route('dashboard') }}" method="GET">
                <div class="relative">
                    <i class="ph-fill ph-calendar-blank absolute left-3 top-2.5 text-gray-400 z-10"></i>
                    
                    <select name="year" onchange="this.form.submit()" 
                            class="pl-10 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold text-gray-700 focus:ring-2 focus:ring-brand-500 outline-none cursor-pointer w-full appearance-none relative z-0 hover:bg-gray-100 transition">
                        
                        @forelse($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                Tahun {{ $year }}
                            </option>
                        @empty
                            <option value="{{ date('Y') }}" selected>
                                Tahun {{ date('Y') }}
                            </option>
                        @endforelse
                        
                    </select>
                    
                    <i class="ph-bold ph-caret-down absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                </div>
            </form>
        </div>

        <div class="w-full h-80"> <canvas id="harvestChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('harvestChart').getContext('2d');
        
        // Data dari Controller (PHP array ke JS)
        const chartData = @json($chartData);
        
        new Chart(ctx, {
            type: 'line', // Jenis grafik: Line (Garis)
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Berat Panen (Kg)',
                    data: chartData,
                    borderColor: '#10B981', // Warna Garis (Brand Green)
                    backgroundColor: 'rgba(16, 185, 129, 0.1)', // Warna Arsir bawah garis
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10B981',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true, // Isi warna di bawah garis
                    tension: 0.4 // Kelengkungan garis (0 = lurus kaku, 0.4 = mulus)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }, // Sembunyikan legenda default
                    tooltip: {
                        backgroundColor: '#064E3B',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.raw + ' Kg';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [5, 5], // Garis putus-putus
                            color: '#f3f4f6'
                        },
                        ticks: { font: { family: 'Poppins' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Poppins' } }
                    }
                }
            }
        });
    </script>
@endsection