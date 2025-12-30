@extends('layouts.app')

@section('content')
<div id="guideModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('guideModal')"></div>
    
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white w-full max-w-md p-6 rounded-2xl shadow-2xl">
        <h3 class="text-lg font-bold mb-4">Tambah Aktifitas</h3>
        
        <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1">Nama Kegiatan</label>
                <input type="text" name="activity" placeholder="Contoh: Pemupukan Urea" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-brand-500 outline-none">
            </div>
            
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1">Tanggal Rencana</label>
                <input type="date" name="date" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-brand-500">
            </div>
            
            <button class="w-full bg-brand-600 text-white py-2 rounded-lg font-bold hover:bg-brand-700 transition">Simpan Jadwal</button>
        </form>
    </div>
</div>

<div class="mb-6 md:mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Jadwal Pemeliharaan</h1>
    <p class="text-gray-500 mt-1 text-sm md:text-base">Jangan lewatkan jadwal pemupukan dan pengendalian hama.</p>
    <button onclick="toggleModal('guideModal')" class="bg-brand-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-700 transition flex items-center gap-2 mt-4 shadow-lg shadow-brand-200">
        <i class="ph-bold ph-plus"></i> Tambah Aktifitas
    </button>
</div>

<div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-400 font-semibold tracking-wider">
                <th class="px-6 py-4">Aktivitas</th>
                <th class="px-6 py-4">Tanggal Rencana</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($schedules as $schedule)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="font-semibold text-gray-800">{{ $schedule['activity'] }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-calendar text-gray-400"></i>
                        {{ date('d M Y', strtotime($schedule['date'])) }}
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($schedule['status'] == 'pending')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>
                    @elseif($schedule['status'] == 'done')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        
                        @if($schedule['status'] == 'pending')
                        <form action="{{ route('schedules.complete', $schedule->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-sm bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg shadow-sm transition-all whitespace-nowrap">
                                Kerjakan
                            </button>
                        </form>
                        @else
                        <span class="text-gray-400 text-sm font-medium px-4 py-2 border border-gray-200 rounded-lg bg-gray-50 cursor-not-allowed">Selesai</span>
                        @endif

                        <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all" title="Hapus">
                                <i class="ph-bold ph-trash text-lg"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="md:hidden space-y-4">
    @foreach($schedules as $schedule)
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex flex-col gap-3">
        
        <div class="flex justify-between items-start gap-4">
            <h3 class="font-bold text-gray-800 text-base leading-tight">{{ $schedule['activity'] }}</h3>
            <div class="flex-shrink-0">
                @if($schedule['status'] == 'pending')
                    <span class="px-2 py-1 rounded-md text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-100">Menunggu</span>
                @else
                    <span class="px-2 py-1 rounded-md text-xs font-bold bg-green-50 text-green-700 border border-green-100">Selesai</span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2 text-sm text-gray-500">
            <i class="ph-fill ph-calendar-blank text-brand-500"></i>
            <span>{{ date('d M Y', strtotime($schedule['date'])) }}</span>
        </div>

        <div class="pt-3 border-t border-gray-50 mt-1 flex gap-3">
            
            <div class="flex-1">
                @if($schedule['status'] == 'pending')
                <form action="{{ route('schedules.complete', $schedule->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="w-full bg-brand-600 text-white py-2.5 rounded-lg font-medium text-sm shadow-sm active:scale-95 transition-transform flex items-center justify-center gap-2">
                        <i class="ph-bold ph-check"></i> Selesai
                    </button>
                </form>
                @else
                <button class="w-full bg-gray-100 text-gray-400 py-2.5 rounded-lg font-medium text-sm cursor-not-allowed flex items-center justify-center gap-2" disabled>
                    <i class="ph-bold ph-check-circle"></i> Beres
                </button>
                @endif
            </div>

            <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                @csrf @method('DELETE')
                <button class="w-12 h-full bg-red-50 text-red-500 rounded-lg flex items-center justify-center hover:bg-red-100 transition-colors active:scale-95">
                    <i class="ph-bold ph-trash text-xl"></i>
                </button>
            </form>
        </div>

    </div>
    @endforeach
</div>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }
</script>
@endsection