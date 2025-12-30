@extends('layouts.app')

@section('content')

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Buku Saku GAP</h1>
        <p class="text-gray-500 mt-1">Good Agricultural Practices sesuai umur tanaman.</p>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        
        <button onclick="toggleModal('guideModal')" class="bg-brand-600 text-white p-3 md:px-4 md:py-2 rounded-lg text-sm font-medium hover:bg-brand-700 transition flex items-center gap-2 shadow-lg shadow-brand-200 flex-shrink-0">
            <i class="ph-bold ph-plus text-lg"></i>
            <span class="hidden md:inline">Tambah Panduan</span>
        </button>

        <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0 hide-scrollbar w-full md:w-auto">
            <button onclick="filterSelection('all', this)" 
                class="filter-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all
                bg-brand-50 border border-brand-200 text-brand-700">
                All
            </button>

            <button onclick="filterSelection('Pembibitan', this)" 
                class="filter-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all
                bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">
                Pembibitan
            </button>

            <button onclick="filterSelection('TBM', this)" 
                class="filter-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all
                bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">
                TBM
            </button>

            <button onclick="filterSelection('TM', this)" 
                class="filter-btn px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition-all
                bg-white border border-gray-200 text-gray-600 hover:bg-gray-50">
                TM
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($guides as $guide)
        
        <div class="guide-item bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 overflow-hidden flex flex-col h-full"
             data-category="{{ $guide->category }}">
             
            <div class="h-2 bg-brand-500 w-full"></div>
            <div class="p-6 flex-1">
                <span class="inline-block px-3 py-1 bg-brand-50 text-brand-600 text-xs font-bold rounded-full mb-3">{{ $guide->category }}</span>
                <h3 class="text-lg font-bold text-gray-800 mb-2 leading-tight">{{ $guide->title }}</h3>
                <p class="text-gray-500 text-sm line-clamp-3">{{ $guide->content }}</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                <button onclick="toggleModal('detailModal-{{ $guide->id }}')" class="text-brand-600 text-sm font-semibold flex items-center gap-1 hover:gap-2 transition-all">
                    Baca Detail <i class="ph-bold ph-arrow-right"></i>
                </button>

                <form action="{{ route('guides.destroy', $guide->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus panduan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition p-1" title="Hapus Panduan">
                        <i class="ph-bold ph-trash text-lg"></i>
                    </button>
                </form>
            </div>
        </div>

        <div id="detailModal-{{ $guide->id }}" class="fixed inset-0 z-50 hidden">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('detailModal-{{ $guide->id }}')"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white w-full max-w-lg p-0 rounded-2xl shadow-2xl overflow-hidden max-h-[85vh] flex flex-col">
                <div class="bg-brand-50 p-6 border-b border-brand-100">
                    <span class="inline-block px-3 py-1 bg-white text-brand-600 text-xs font-bold rounded-full mb-2 border border-brand-200">{{ $guide->category }}</span>
                    <h3 class="text-xl font-bold text-gray-900">{{ $guide->title }}</h3>
                </div>
                <div class="p-6 overflow-y-auto">
                    <p class="text-gray-600 text-base leading-relaxed whitespace-pre-line">{{ $guide->content }}</p>
                </div>
                <div class="p-4 border-t border-gray-100 bg-gray-50 text-right">
                    <button onclick="toggleModal('detailModal-{{ $guide->id }}')" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">Tutup</button>
                </div>
            </div>
        </div>

        @endforeach
    </div>

    <div id="guideModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="toggleModal('guideModal')"></div>
        
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white w-full max-w-md p-6 rounded-2xl shadow-2xl">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                <i class="ph-fill ph-book-open-text text-brand-600"></i> Tambah Buku Saku
            </h3>
            
            <form action="{{ route('guides.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Materi</label>
                    <input type="text" name="title" placeholder="Contoh: Cara Pemupukan TBM" required class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-brand-500 focus:bg-white outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Fase</label>
                    <div class="relative">
                        <i class="ph-fill ph-caret-down absolute right-4 top-3.5 text-gray-400 pointer-events-none"></i>
                        <select name="category" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2.5 outline-none appearance-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all">
                            <option value="Pembibitan">Pembibitan</option>
                            <option value="TBM">TBM (Belum Menghasilkan)</option>
                            <option value="TM">TM (Menghasilkan)</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Isi Materi</label>
                    <textarea name="content" rows="5" placeholder="Tulis langkah-langkah detail di sini..." required class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-brand-500 focus:bg-white transition-all"></textarea>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="toggleModal('guideModal')" class="flex-1 bg-gray-100 text-gray-600 py-2.5 rounded-lg font-bold hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="flex-1 bg-brand-600 text-white py-2.5 rounded-lg font-bold hover:bg-brand-700 transition shadow-lg shadow-brand-200">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle Modal
        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }

        // Logic Filter Kategori
        function filterSelection(category, btnElement) {
            var x, i;
            x = document.getElementsByClassName("guide-item");
            
            if (category == "all") category = "";
            for (i = 0; i < x.length; i++) {
                removeShowClass(x[i], "hidden");
                // Cek apakah data-category cocok
                if (x[i].getAttribute("data-category").indexOf(category) == -1) {
                    addShowClass(x[i], "hidden");
                }
            }

            // Update Warna Tombol
            var btns = document.getElementsByClassName("filter-btn");
            var inactiveClass = "bg-white border border-gray-200 text-gray-600 hover:bg-gray-50".split(" ");
            var activeClass = "bg-brand-50 border border-brand-200 text-brand-700".split(" ");

            for (i = 0; i < btns.length; i++) {
                btns[i].classList.remove(...activeClass);
                btns[i].classList.add(...inactiveClass);
            }
            btnElement.classList.remove(...inactiveClass);
            btnElement.classList.add(...activeClass);
        }

        // Helper Class Manipulation
        function addShowClass(element, name) {
            var arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++) {
                if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
            }
        }

        function removeShowClass(element, name) {
            var arr1, arr2;
            arr1 = element.className.split(" ");
            arr2 = name.split(" ");
            for (i = 0; i < arr2.length; i++) {
                while (arr1.indexOf(arr2[i]) > -1) {
                    arr1.splice(arr1.indexOf(arr2[i]), 1);     
                }
            }
            element.className = arr1.join(" ");
        }
    </script>
@endsection