@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Logbook Aktivitas</h1>
            <p class="text-gray-500 mt-1 text-sm">Pencatatan wajib bahan kimia & pupuk (Syarat ISPO).</p>
        </div>

        <a href="{{ route('logbook.export') }}" class="w-full md:w-auto bg-gray-900 text-white px-3 py-2 md:py-1.5 rounded-xl font-medium shadow-lg hover:shadow-xl hover:bg-black transition-all flex items-center justify-center gap-2 active:scale-95">
            <i class="ph-bold ph-file-pdf text-lg"></i> 
            <span>Export PDF</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
                <div class="flex items-center gap-2 mb-4 border-b border-gray-100 pb-4">
                    <div class="bg-brand-50 text-brand-600 p-2 rounded-lg">
                        <i class="ph-fill ph-pencil-simple text-lg"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Catat Kegiatan</h3>
                </div>
                
                <form action="{{ route('logbook.store') }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ date('Y-m-d') }}"> 
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Jenis Kegiatan</label>
                        <div class="relative">
                            <i class="ph-fill ph-caret-down absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                            <select name="activity_type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white outline-none appearance-none transition-all font-medium text-gray-700">
                                <option value="Pemupukan">Pemupukan</option>
                                <option value="Penyemprotan Hama">Penyemprotan Hama</option>
                                <option value="Perawatan Piringan">Perawatan Piringan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Bahan (Merk)</label>
                        <input type="text" name="material" required placeholder="Contoh: NPK Mutiara" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1.5">Dosis Total</label>
                        <div class="flex">
                            <input type="text" name="dose" required placeholder="0" class="w-full bg-gray-50 border border-gray-200 rounded-l-xl px-4 py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white outline-none transition-all">
                            <span class="bg-gray-100 border border-l-0 border-gray-200 text-gray-500 text-sm font-medium px-4 flex items-center justify-center rounded-r-xl">Kg/L</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3.5 rounded-xl shadow-md shadow-brand-200 transition-all mt-2 active:scale-95 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-floppy-disk"></i> Simpan Data
                    </button>
                </div>
            </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <h3 class="font-bold text-gray-800 mb-4 px-1 text-lg">Riwayat Bulan Ini</h3>

            <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-400 font-semibold">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Kegiatan</th>
                            <th class="px-6 py-4">Bahan & Dosis</th>
                            <th class="px-6 py-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($logs as $log)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                {{ date('d M Y', strtotime($log['date'])) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold 
                                    {{ $log['activity_type'] == 'Pemupukan' ? 'bg-blue-50 text-blue-700' : 'bg-orange-50 text-orange-700' }}">
                                    <i class="ph-fill {{ $log['activity_type'] == 'Pemupukan' ? 'ph-drop' : 'ph-bug' }}"></i>
                                    {{ $log['activity_type'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 font-semibold">{{ $log['material'] }}</span>
                                    <span class="text-gray-400 text-xs">{{ $log['dose'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                            <form action="{{ route('logbook.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors p-2">
                                    <i class="ph-fill ph-trash text-xl"></i>
                                </button>
                            </form>
                        </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="md:hidden space-y-3">
                @foreach($logs as $log)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col gap-3">
                    
                    <div class="flex justify-between items-start">
                        <div class="text-xs text-gray-400 font-medium flex items-center gap-1">
                            <i class="ph-fill ph-calendar-blank"></i>
                            {{ date('d M Y', strtotime($log['date'])) }}
                        </div>
                        <span class="px-2 py-1 rounded-md text-xs font-bold border 
                            {{ $log['activity_type'] == 'Pemupukan' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                            {{ $log['activity_type'] }}
                        </span>
                        <form action="{{ route('logbook.destroy', $log->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                            @csrf @method('DELETE')
                            <button class="text-gray-300 hover:text-red-500 p-1"><i class="ph-fill ph-trash text-lg"></i></button>
                        </form>
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-50 pt-3">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Bahan</p>
                            <p class="text-gray-900 font-bold text-base">{{ $log['material'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Dosis</p>
                            <p class="text-brand-600 font-bold text-base">{{ $log['dose'] }}</p>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

        </div>
    </div>
@endsection