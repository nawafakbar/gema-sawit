@extends('layouts.app')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Buku Panen Digital</h1>
        <p class="text-gray-500 mt-1">Rekapitulasi hasil Tandan Buah Segar (TBS).</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 text-lg">Input Hasil Panen</h3>
                <form action="{{ route('harvests.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Tanggal Panen</label>
                        <input type="date" name="date" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-brand-500">
                    </div>
                    <div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 block mb-1">Lokasi Blok</label>
                            
                            <input type="text" list="block_suggestions" name="block" required 
                                placeholder="Ketik nama blok/lahan..." 
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-brand-500 transition-all">
                            
                            <datalist id="block_suggestions">
                                @foreach(\App\Models\Harvest::select('block')->distinct()->get() as $b)
                                    <option value="{{ $b->block }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Total Berat (Kg)</label>
                        <div class="relative">
                            <input type="number" name="weight_kg" required placeholder="0" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 text-sm outline-none focus:border-brand-500 pl-4 pr-12">
                            <span class="absolute right-4 top-2.5 text-gray-400 text-sm">Kg</span>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-semibold py-3 rounded-xl shadow-md transition-all">
                        Simpan Data
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            @foreach($harvests as $harvest)
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between hover:border-brand-200 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                        <i class="ph-fill ph-truck text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $harvest['weight_kg'] }} Kg</h4>
                        <p class="text-sm text-gray-500">{{ $harvest['block'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900">{{ date('d M Y', strtotime($harvest['date'])) }}</p>
                    <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-md mt-1 inline-block">Terverifikasi</span>
                </div>
                <form action="{{ route('harvests.destroy', $harvest->id) }}" method="POST" onsubmit="return confirm('Hapus data panen ini?');">
                    @csrf @method('DELETE')
                    <button class="w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                        <i class="ph-bold ph-trash"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
@endsection