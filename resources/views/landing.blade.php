<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPalm - Revolusi Digital & IoT Sawit</title>
    <link rel="icon" href="{{ asset('Images/favicon.svg') }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('Images/favicon.svg') }}" type="image/svg+xml">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { 
                        brand: { 50: '#ECFDF5', 100: '#D1FAE5', 500: '#10B981', 600: '#059669', 900: '#064E3B' },
                        dark: { 900: '#111827' }
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>
    <style>
        .blob { position: absolute; filter: blur(40px); z-index: -1; opacity: 0.4; }
        .text-gradient { background-clip: text; -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-image: linear-gradient(to right, #059669, #10B981); }
    </style>
</head>
<body class="bg-white font-sans text-gray-800 antialiased overflow-x-hidden">

    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <a href="#" class="flex items-center gap-2 group">
                <div class="bg-brand-50 p-2 rounded-lg group-hover:bg-brand-100 transition">
                    <i class="ph-fill ph-plant text-brand-600 text-2xl"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-900">Smart<span class="text-brand-500">Palm</span></span>
            </a>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#ninja-iot" class="text-sm font-bold text-brand-600 hover:text-brand-800 transition flex items-center gap-1">
                    <i class="ph-bold ph-broadcast"></i> Ninja Sawit IoT
                </a>
                <a href="#fitur" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition">Fitur Unggulan</a>
                <a href="#ai-demo" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition">AI Technology</a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-brand-600 text-white text-sm font-semibold rounded-full hover:bg-brand-700 transition shadow-lg shadow-brand-200 hover:-translate-y-0.5 transform duration-200">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 bg-gray-900 text-white text-sm font-semibold rounded-full hover:bg-black transition flex items-center gap-2 shadow-lg hover:-translate-y-0.5 transform duration-200">
                        <i class="ph-bold ph-sign-in"></i> Login Petani
                    </a>
                @endauth
            </div>

            <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-gray-600 hover:text-brand-600 transition">
                <i class="ph-bold ph-list text-2xl" id="menuIcon"></i>
            </button>
        </div>

        <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-100 absolute w-full shadow-xl">
            <div class="px-6 py-4 flex flex-col gap-4">
                <a href="#ninja-iot" class="text-brand-600 font-bold py-2 border-b border-gray-50">Ninja Sawit IoT</a>
                <a href="#fitur" class="text-gray-600 font-medium py-2 border-b border-gray-50">Fitur Unggulan</a>
                <a href="#ai-demo" class="text-gray-600 font-medium py-2 border-b border-gray-50">Teknologi AI</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full text-center px-5 py-3 bg-brand-600 text-white font-bold rounded-xl">Ke Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="w-full text-center px-5 py-3 bg-gray-900 text-white font-bold rounded-xl">Login Petani</a>
                @endauth
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-30 lg:pb-32 overflow-hidden bg-gray-50">
        <div class="blob bg-brand-200 w-96 h-96 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="blob bg-blue-100 w-96 h-96 rounded-full bottom-0 right-0 translate-x-1/2 translate-y-1/2"></div>

        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center relative z-10">
            <div class="text-center lg:text-left">
                <span class="inline-flex items-center gap-2 py-1 px-3 rounded-full bg-white text-brand-700 text-xs font-bold mb-6 border border-brand-100 shadow-sm">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    Solusi Agronomi & Keamanan IoT
                </span>
                
                <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                    Pantau & Amankan <br> 
                    <span class="text-gradient">Kebun Sawit</span> Anda
                </h1>
                
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Gabungan kecerdasan buatan (AI) untuk agronomi dan sistem <b>Ninja Sawit IoT</b> untuk keamanan anti-maling 24 jam. Bertani tenang, hasil maksimal.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="#ninja-iot" class="px-8 py-4 bg-brand-600 text-white font-bold rounded-xl shadow-xl shadow-brand-200 hover:bg-brand-700 hover:scale-105 transition transform duration-200 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-broadcast"></i> Cek Ninja Sawit
                    </a>
                    <a href="https://gapki.id/" target="_blank" class="px-8 py-4 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition flex items-center justify-center gap-2 group">
                        Info Industri <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <div class="mt-10 flex items-center justify-center lg:justify-start gap-8 pt-8 border-t border-gray-200/60">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">IoT</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Monitoring</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">AI</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Diagnosis</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">GAP</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Standard</p>
                    </div>
                </div>
            </div>

            <div class="relative hidden lg:block">
                <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl border-4 border-white transform rotate-2 hover:rotate-0 transition duration-500">
                    <img src="{{ asset('Images/sawitnih.png') }}" class="w-full h-auto object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 text-white">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                            <p class="font-bold text-lg">Ninja Sawit Aktif</p>
                        </div>
                        <p class="text-sm opacity-80 pl-5">Area Aman & Terpantau</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="masalah" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="order-2 md:order-1">
                    <div class="bg-red-50 p-8 rounded-3xl border border-red-100 mb-6">
                        <h3 class="text-xl font-bold text-red-700 mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-warning-circle"></i> Tantangan Petani Saat Ini
                        </h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start gap-2 font-semibold text-gray-900">
                                <i class="ph-bold ph-siren text-red-600 mt-1 text-lg"></i> 
                                Rawan pencurian buah (Ninja Sawit solusinya).
                            </li>
                            <li class="flex items-start gap-2"><i class="ph-bold ph-x text-red-400 mt-1"></i> Lokasi kebun jauh & sulit dipantau 24 jam.</li>
                            <li class="flex items-start gap-2"><i class="ph-bold ph-x text-red-400 mt-1"></i> Tidak tahu jadwal pupuk & dosis yang pas.</li>
                            <li class="flex items-start gap-2"><i class="ph-bold ph-x text-red-400 mt-1"></i> Penyakit tanaman terlambat dideteksi.</li>
                        </ul>
                    </div>
                </div>
                <div class="order-1 md:order-2">
                    <span class="text-brand-600 font-bold text-sm uppercase tracking-wider">Mengapa SmartPalm?</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-4">Keamanan & Produktivitas Jadi Satu</h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Kami paham bahwa merawat sawit bukan cuma soal pupuk, tapi juga menjaga aset dari pencurian. SmartPalm menggabungkan agronomi digital dengan sistem keamanan IoT "Ninja Sawit".
                    </p>
                    <div class="bg-brand-50 p-6 rounded-2xl border-l-4 border-brand-500">
                        <p class="text-brand-900 font-medium italic">
                            "Satu aplikasi untuk diagnosa penyakit, catatan panen, dan alarm anti-maling."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="ninja-iot" class="py-24 bg-gray-900 text-white relative overflow-hidden border-y-8 border-brand-500 shadow-2xl">
        
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-30 pointer-events-none">
             <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-brand-600 blur-[100px] animate-pulse-slow"></div>
             <div class="absolute bottom-0 right-0 w-[500px] h-[500px] rounded-full bg-blue-600 blur-[120px] animate-pulse-slow"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block py-1 px-4 rounded-full bg-brand-500 text-white font-bold text-xs uppercase tracking-wider mb-4 shadow-lg shadow-brand-500/50">
                    🚀 Teknologi Andalan
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold mt-2 tracking-tight">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 to-blue-400">Ninja Sawit</span> IoT System
                </h2>
                <p class="text-gray-300 mt-4 max-w-2xl mx-auto leading-relaxed text-lg">
                    Sistem keamanan dan monitoring kebun tercanggih. Mendeteksi suara mencurigakan (maling) dan memantau kondisi lingkungan 24/7 secara realtime.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <div class="bg-gray-800/80 backdrop-blur-md p-6 rounded-2xl border border-gray-700 hover:border-brand-500 transition group hover:-translate-y-2 duration-300 shadow-xl">
                    <div class="w-14 h-14 bg-gray-900 rounded-xl flex items-center justify-center text-brand-400 mb-4 group-hover:bg-brand-500 group-hover:text-white transition shadow-inner">
                        <i class="ph-fill ph-broadcast text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2 text-white">Sensor Anti-Maling</h4>
                    <p class="text-gray-400 text-sm">Mendeteksi tingkat kebisingan (dB) tinggi di jam rawan dan mengirim alert instan ke pemilik.</p>
                </div>

                <div class="bg-gray-800/80 backdrop-blur-md p-6 rounded-2xl border border-gray-700 hover:border-brand-500 transition group hover:-translate-y-2 duration-300 shadow-xl">
                    <div class="w-14 h-14 bg-gray-900 rounded-xl flex items-center justify-center text-blue-400 mb-4 group-hover:bg-blue-500 group-hover:text-white transition shadow-inner">
                        <i class="ph-fill ph-globe-hemisphere-west text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2 text-white">Pemetaan Satelit</h4>
                    <p class="text-gray-400 text-sm">Visualisasi lokasi sensor dan kondisi lahan via peta satelit interaktif untuk pemantauan akurat.</p>
                </div>

                <div class="bg-gray-800/80 backdrop-blur-md p-6 rounded-2xl border border-gray-700 hover:border-brand-500 transition group hover:-translate-y-2 duration-300 shadow-xl">
                    <div class="w-14 h-14 bg-gray-900 rounded-xl flex items-center justify-center text-green-400 mb-4 group-hover:bg-green-500 group-hover:text-white transition shadow-inner">
                        <i class="ph-fill ph-telegram-logo text-3xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2 text-white">Notifikasi Telegram</h4>
                    <p class="text-gray-400 text-sm">Laporan kondisi baterai sensor dan peringatan bahaya dikirim langsung ke WhatsApp/Telegram.</p>
                </div>
            </div>
            
            <div class="relative mx-auto max-w-5xl">
                <div class="absolute inset-0 bg-gradient-to-r from-brand-500 to-blue-500 rounded-3xl blur-2xl opacity-40"></div>
                
                <div class="bg-gray-800 rounded-3xl p-2 border border-gray-600 shadow-2xl relative">
                     <div class="bg-gray-900 rounded-2xl overflow-hidden relative border border-gray-800">
                         <div class="h-10 bg-gray-800 border-b border-gray-700 flex items-center px-4 gap-2 justify-between">
                            <div class="flex gap-2">
                                 <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                 <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                 <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="bg-black/50 px-4 py-1 rounded text-[10px] text-gray-500 font-mono tracking-wider">
                                dashboard.smartpalm.id/monitoring/ninja-sawit
                            </div>
                         </div>
                         
                         <div class="p-8 grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase font-bold">Status Sensor</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="w-3 h-3 rounded-full bg-green-500 animate-ping"></span>
                                    <h4 class="text-2xl font-bold text-white">ONLINE</h4>
                                </div>
                            </div>
                            <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase font-bold">Kebisingan</p>
                                <h4 class="text-2xl font-bold text-white mt-2">45 <span class="text-sm font-normal text-gray-500">dB</span></h4>
                            </div>
                             <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase font-bold">Baterai Device</p>
                                <h4 class="text-2xl font-bold text-green-400 mt-2">87%</h4>
                            </div>
                             <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                                <p class="text-xs text-gray-400 uppercase font-bold">Total Alert</p>
                                <h4 class="text-2xl font-bold text-red-400 mt-2">0 <span class="text-sm font-normal text-gray-500">Hari ini</span></h4>
                            </div>
                            
                            <div class="col-span-full h-48 bg-gray-800 rounded-xl border border-gray-700 flex items-center justify-center relative overflow-hidden group">
                                <div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(0deg, transparent 24%, #374151 25%, #374151 26%, transparent 27%, transparent 74%, #374151 75%, #374151 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, #374151 25%, #374151 26%, transparent 27%, transparent 74%, #374151 75%, #374151 76%, transparent 77%, transparent); background-size: 30px 30px;"></div>
                                <div class="text-center z-10">
                                    <i class="ph-duotone ph-chart-line-up text-5xl text-brand-600 mb-2 group-hover:scale-110 transition"></i>
                                    <p class="text-xs text-gray-500 font-mono font-bold tracking-widest">REALTIME DATA STREAM VISUALIZATION</p>
                                </div>
                            </div>
                         </div>
                     </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-20 bg-gray-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Fitur Pendukung Lainnya</h2>
                <p class="text-gray-500 mt-2">Lengkap dari hulu ke hilir untuk petani sawit.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm hover:shadow-lg transition group duration-300">
                    <div class="w-14 h-14 bg-brand-50 rounded-2xl flex items-center justify-center text-brand-600 mb-6 group-hover:bg-brand-500 group-hover:text-white transition">
                        <i class="ph-fill ph-book-open-text text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Buku Saku GAP</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">
                        Panduan lengkap budidaya standar sesuai umur tanaman (TBM/TM). Tidak perlu bingung lagi soal dosis dan rotasi.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm hover:shadow-lg transition group duration-300 relative overflow-hidden">
                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-500 group-hover:text-white transition">
                        <i class="ph-fill ph-robot text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Dr. Sawit AI</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">
                        Cukup foto daun atau buah, AI kami akan mendiagnosa penyakit dan menentukan kematangan buah dalam hitungan detik.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm hover:shadow-lg transition group duration-300">
                    <div class="w-14 h-14 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-500 group-hover:text-white transition">
                        <i class="ph-fill ph-file-text text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Logbook ISPO</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">
                        Pencatatan aktivitas kebun dan penggunaan bahan kimia secara digital. Export PDF siap cetak untuk audit sertifikasi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="ai-demo" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                <div class="relative flex justify-center">
                    <div class="absolute inset-0 bg-brand-200 rounded-full filter blur-3xl opacity-30 transform scale-75"></div>
                    <div class="relative w-72 bg-gray-900 rounded-[3rem] border-8 border-gray-900 shadow-2xl overflow-hidden">
                        <div class="absolute top-0 inset-x-0 h-6 bg-gray-900 rounded-b-xl z-20 w-32 mx-auto"></div>
                        <div class="bg-white h-[550px] flex flex-col pt-10 px-4 pb-4">
                            <div class="flex gap-2 mb-4">
                                <div class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 text-xs font-bold">AI</div>
                                <div class="bg-gray-100 p-3 rounded-2xl rounded-tl-none text-xs text-gray-700 shadow-sm">
                                    Halo Pak! Kirim foto daun sawitnya, saya cek kondisinya.
                                </div>
                            </div>
                            <div class="flex gap-2 mb-4 flex-row-reverse">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-xs font-bold">P</div>
                                <div class="bg-brand-600 p-1 rounded-2xl rounded-tr-none text-white shadow-sm max-w-[70%]">
                                    <img src="{{ asset('Images/ulat.jpeg') }}">
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="w-8 h-8 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 text-xs font-bold">AI</div>
                                <div class="bg-white border border-brand-100 p-3 rounded-2xl rounded-tl-none text-xs text-gray-700 shadow-sm">
                                    <p class="font-bold text-brand-600 mb-1"><i class="ph-fill ph-warning"></i> Terdeteksi: Ulat Api</p>
                                    Daun menunjukkan gejala serangan <i>Setothosea asigna</i>. Segera lakukan sensus populasi di blok ini.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <span class="text-brand-600 font-bold text-sm uppercase tracking-wider">Fitur Cerdas</span>
                    <h2 class="text-4xl font-bold text-gray-900 mt-2 mb-6">Konsultasi AI 24 Jam</h2>
                    <p class="text-lg text-gray-600 mb-8">
                        Fitur tambahan Dr. Sawit membantu Anda mendiagnosa penyakit tanaman secara instan. Pelengkap sempurna untuk sistem monitoring Ninja Sawit Anda.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-900 text-white border-t border-gray-800">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-6">Siap Meningkatkan Hasil Kebun?</h2>
            <p class="text-gray-400 mb-8 text-lg">
                Bergabunglah dengan transformasi pertanian digital. Amankan kebun dengan Ninja Sawit sekarang.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="px-8 py-4 bg-brand-600 text-white font-bold rounded-xl shadow-xl shadow-brand-900 hover:bg-brand-500 hover:-translate-y-1 transition transform duration-200">
                    Masuk Sekarang
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-gray-950 border-t border-gray-800 py-12 text-gray-400">
        <div class="max-w-7xl mx-auto px-6 text-center">
             <div class="flex items-center justify-center gap-2 mb-4">
                <i class="ph-fill ph-plant text-brand-600 text-2xl"></i>
                <span class="text-xl font-bold text-white">Smart<span class="text-brand-500">Palm</span></span>
            </div>
            <p class="text-xs">
                &copy; {{ date('Y') }} Riset Teknologi Pertanian Modern. Dibuat dengan <i class="ph-fill ph-heart text-red-500"></i> untuk Petani Indonesia.
            </p>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('menuIcon');
            
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.classList.remove('ph-list');
                icon.classList.add('ph-x');
            } else {
                menu.classList.add('hidden');
                icon.classList.remove('ph-x');
                icon.classList.add('ph-list');
            }
        }
    </script>
</body>
</html>