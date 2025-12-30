@extends('layouts.app')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="w-full md:w-auto">
            <h1 class="text-2xl font-bold text-gray-900">Laporan Keuangan</h1>
            <p class="text-gray-500 mt-1">Catat arus kas masuk dan keluar kebun.</p>
        </div>
        
        <form action="{{ url('/dashboard/finance') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <select name="month" onchange="this.form.submit()" class="flex-1 bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium outline-none focus:ring-2 focus:ring-brand-500">
                @for($i=1; $i<=12; $i++)
                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                @endfor
            </select>
            <select name="year" onchange="this.form.submit()" class="flex-1 bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm font-medium outline-none focus:ring-2 focus:ring-brand-500">
                @for($y=date('Y'); $y>=date('Y')-2; $y--)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Pemasukan</p>
                <h3 class="text-xl md:text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($total_income, 0, ',', '.') }}</h3>
            </div>
            <div class="absolute right-0 top-0 p-4 opacity-10">
                <i class="ph-fill ph-trend-up text-5xl md:text-6xl text-green-600"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Pengeluaran</p>
                <h3 class="text-xl md:text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($total_expense, 0, ',', '.') }}</h3>
            </div>
            <div class="absolute right-0 top-0 p-4 opacity-10">
                <i class="ph-fill ph-trend-down text-5xl md:text-6xl text-red-500"></i>
            </div>
        </div>

        <div class="bg-white from-gray-900 to-gray-800 p-5 rounded-2xl shadow-sm text-black relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Keuntungan Bersih</p>
                <h3 class="text-xl md:text-2xl font-bold mt-1">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                <p class="text-xs {{ $saldo >= 0 ? 'text-green-400' : 'text-red-400' }} mt-1">
                    {{ $saldo >= 0 ? 'Positif (Untung)' : 'Negatif (Rugi)' }}
                </p>
            </div>
            <div class="absolute right-0 top-0 p-4 opacity-20">
                <i class="ph-fill ph-wallet text-5xl md:text-6xl text-white"></i>
            </div>
        </div>
    </div>

    <div class="mb-6 md:text-right">
        <button onclick="toggleModal('financeModal')" class="w-full md:w-auto bg-brand-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-brand-200 hover:bg-brand-700 transition flex items-center justify-center gap-2 md:ml-auto active:scale-95">
            <i class="ph-bold ph-plus-circle text-xl"></i> Catat Transaksi
        </button>
    </div>

    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-400 font-semibold">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Kategori & Ket</th>
                    <th class="px-6 py-4 text-right">Nominal</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $trx)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ date('d M Y', strtotime($trx->date)) }}
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-800">{{ $trx->category }}</p>
                        <p class="text-xs text-gray-400">{{ $trx->description ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 text-right font-bold {{ $trx->type == 'income' ? 'text-green-600' : 'text-red-500' }}">
                        {{ $trx->type == 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <form action="{{ route('finance.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?');">
                            @csrf @method('DELETE')
                            <button class="text-gray-300 hover:text-red-500 transition"><i class="ph-fill ph-trash text-xl"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                        Belum ada data transaksi bulan ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="md:hidden space-y-3">
        @forelse($transactions as $trx)
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col gap-3">
            
            <div class="flex justify-between items-start">
                <div class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                    <i class="ph-fill ph-calendar-blank"></i>
                    {{ date('d M Y', strtotime($trx->date)) }}
                </div>
                
                @if($trx->type == 'income')
                    <span class="bg-green-50 text-green-700 p-1.5 rounded-lg">
                        <i class="ph-bold ph-arrow-down-left text-lg"></i>
                    </span>
                @else
                    <span class="bg-red-50 text-red-700 p-1.5 rounded-lg">
                        <i class="ph-bold ph-arrow-up-right text-lg"></i>
                    </span>
                @endif
            </div>

            <div>
                <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $trx->category }}</h4>
                <p class="text-sm text-gray-500 mt-1">{{ $trx->description ?? 'Tidak ada catatan' }}</p>
            </div>

            <div class="flex justify-between items-center border-t border-gray-50 pt-3 mt-1">
                <span class="font-bold text-lg {{ $trx->type == 'income' ? 'text-green-600' : 'text-red-500' }}">
                    {{ $trx->type == 'income' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                </span>

                <form action="{{ route('finance.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                    @csrf @method('DELETE')
                    <button class="text-gray-300 hover:text-red-500 p-1 active:scale-95">
                        <i class="ph-fill ph-trash text-xl"></i>
                    </button>
                </form>
            </div>

        </div>
        @empty
        <div class="text-center py-8 text-gray-400 bg-white rounded-xl border border-dashed border-gray-200">
            <i class="ph ph-receipt text-4xl mb-2 opacity-30"></i>
            <p>Belum ada transaksi.</p>
        </div>
        @endforelse
    </div>

    <div id="financeModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('financeModal')"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white w-full max-w-md p-6 rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold mb-4">Catat Keuangan</h3>
            
            <form action="{{ route('finance.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2 outline-none focus:border-brand-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="income" class="peer sr-only" checked>
                        <div class="p-3 text-center border border-gray-200 rounded-lg peer-checked:bg-green-50 peer-checked:border-green-500 peer-checked:text-green-700 hover:bg-gray-50 transition">
                            <i class="ph-fill ph-arrow-down-left mb-1 text-lg"></i>
                            <span class="block text-sm font-bold">Pemasukan</span>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="expense" class="peer sr-only">
                        <div class="p-3 text-center border border-gray-200 rounded-lg peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 hover:bg-gray-50 transition">
                            <i class="ph-fill ph-arrow-up-right mb-1 text-lg"></i>
                            <span class="block text-sm font-bold">Pengeluaran</span>
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <input type="text" name="category" list="cat_suggestions" placeholder="Contoh: Jual Sawit / Beli Pupuk" required class="w-full border border-gray-200 rounded-lg px-4 py-2 outline-none focus:border-brand-500">
                    <datalist id="cat_suggestions">
                        <option value="Penjualan TBS (Panen)">
                        <option value="Upah Panen">
                        <option value="Beli Pupuk">
                        <option value="Beli Racun/Pestisida">
                        <option value="Biaya Transport/Langsir">
                    </datalist>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" placeholder="0" required class="w-full border border-gray-200 rounded-lg px-4 py-2 outline-none focus:border-brand-500 font-bold text-gray-800">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea name="description" rows="2" class="w-full border border-gray-200 rounded-lg px-4 py-2 outline-none focus:border-brand-500"></textarea>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="toggleModal('financeModal')" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl font-bold hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="flex-1 bg-gray-900 text-white py-3 rounded-xl font-bold hover:bg-black transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }
    </script>
@endsection