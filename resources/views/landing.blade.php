<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPalm - Revolusi Digital Sawit Rakyat</title>
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
                <a href="#masalah" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition">Masalah & Solusi</a>
                <a href="#fitur" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition">Fitur Unggulan</a>
                <a href="#ai-demo" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition">Teknologi AI</a>
                <a href="#iot-monitoring" class="text-sm font-medium text-gray-600 hover:text-brand-600 transition">Monitoring IoT</a>
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
                <a href="#masalah" class="text-gray-600 font-medium py-2 border-b border-gray-50">Masalah & Solusi</a>
                <a href="#fitur" class="text-gray-600 font-medium py-2 border-b border-gray-50">Fitur Unggulan</a>
                <a href="#ai-demo" class="text-gray-600 font-medium py-2 border-b border-gray-50">Teknologi AI</a>
                <a href="#iot-monitoring" class="text-gray-600 font-medium py-2 border-b border-gray-50">Monitoring IoT</a>
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
                    <span class="w-2 h-2 bg-brand-500 rounded-full animate-pulse"></span>
                    Solusi Digital Agronomi & ISPO
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                    Optimalkan Hasil <br> 
                    <span class="text-gradient">Sawit Rakyat</span> Indonesia
                </h1>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Platform pintar untuk memantau budidaya, menjadwalkan pemupukan, dan deteksi penyakit tanaman berbasis AI. Bertani lebih cerdas, hasil lebih melimpah.
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="#fitur" class="px-8 py-4 bg-brand-600 text-white font-bold rounded-xl shadow-xl shadow-brand-200 hover:bg-brand-700 hover:scale-105 transition transform duration-200">
                        Mulai Jelajahi
                    </a>
                    <a href="https://gapki.id/" target="_blank" class="px-8 py-4 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition flex items-center justify-center gap-2 group">
                        Info Industri <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                <div class="mt-10 flex items-center justify-center lg:justify-start gap-8 pt-8 border-t border-gray-200/60">
                    <div>
                        <p class="text-2xl font-bold text-gray-900">GAP</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Standard</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">AI</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Technology</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">ISPO</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Ready</p>
                    </div>
                </div>
            </div>

            <div class="relative hidden lg:block">
                <div class="relative z-10 rounded-3xl overflow-hidden shadow-2xl border-4 border-white transform rotate-2 hover:rotate-0 transition duration-500">
                    <img src="{{ asset('Images/sawitnih.png') }}" class="w-full h-auto object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 text-white">
                        <p class="font-bold text-lg">Monitoring Lahan</p>
                        <p class="text-sm opacity-80">Real-time data & analysis</p>
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
                            <i class="ph-fill ph-warning-circle"></i> Masalah Petani Saat Ini
                        </h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start gap-2"><i class="ph-bold ph-x text-red-500 mt-1"></i> Marak nya aksi ninja-sawit berdampak terhadap kerugian finansial</li>
                            <li class="flex items-start gap-2"><i class="ph-bold ph-x text-red-500 mt-1"></i> Jadwal pupuk tidak teratur & lupa dosis.</li>
                            <li class="flex items-start gap-2"><i class="ph-bold ph-x text-red-500 mt-1"></i> Sulit mendeteksi penyakit dini pada daun.</li>
                            <li class="flex items-start gap-2"><i class="ph-bold ph-x text-red-500 mt-1"></i> Tidak ada catatan panen untuk evaluasi hasil.</li>
                            <li class="flex items-start gap-2"><i class="ph-bold ph-x text-red-500 mt-1"></i> Kesulitan memenuhi syarat administrasi ISPO.</li>
                        </ul>
                    </div>
                </div>
                <div class="order-1 md:order-2">
                    <span class="text-brand-600 font-bold text-sm uppercase tracking-wider">Latar Belakang</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-4">Kenapa Produktivitas Rendah?</h2>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Banyak petani rakyat memiliki potensi lahan yang besar, namun terhambat oleh kurangnya akses informasi agronomi yang tepat, manajemen kebun yang masih tradisional, dan kurang nya sistem keamanan yang terintegrasi.
                    </p>
                    <div class="bg-brand-50 p-6 rounded-2xl border-l-4 border-brand-500">
                        <p class="text-brand-900 font-medium italic">
                            "SmartPalm hadir sebagai jembatan teknologi untuk menstandardisasi perawatan dan keamanan kebun rakyat setara dengan kebun perusahaan."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-20 bg-gray-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold">Fitur Super SmartPalm</h2>
                <p class="text-gray-400 mt-2">Teknologi canggih dalam genggaman tangan.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gray-800 p-8 rounded-3xl border border-gray-700 hover:border-brand-500 hover:bg-gray-800/80 transition group duration-300">
                    <div class="w-14 h-14 bg-gray-700 rounded-2xl flex items-center justify-center text-brand-400 mb-6 group-hover:bg-brand-500 group-hover:text-white transition">
                        <i class="ph-fill ph-book-open-text text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Buku Saku GAP</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Panduan lengkap budidaya standar sesuai umur tanaman (TBM/TM). Tidak perlu bingung lagi soal dosis dan rotasi.
                    </p>
                </div>

                <div class="bg-gray-800 p-8 rounded-3xl border border-gray-700 hover:border-brand-500 hover:bg-gray-800/80 transition group duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-brand-600 text-xs font-bold px-3 py-1 rounded-bl-xl text-white">UNGGULAN</div>
                    <div class="w-14 h-14 bg-gray-700 rounded-2xl flex items-center justify-center text-brand-400 mb-6 group-hover:bg-brand-500 group-hover:text-white transition">
                        <i class="ph-fill ph-robot text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Dr. Sawit AI</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Cukup foto daun atau buah, AI kami akan mendiagnosa penyakit dan menentukan kematangan buah dalam hitungan detik.
                    </p>
                </div>

                <div class="bg-gray-800 p-8 rounded-3xl border border-gray-700 hover:border-brand-500 hover:bg-gray-800/80 transition group duration-300">
                    <div class="w-14 h-14 bg-gray-700 rounded-2xl flex items-center justify-center text-brand-400 mb-6 group-hover:bg-brand-500 group-hover:text-white transition">
                        <i class="ph-fill ph-file-text text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Logbook ISPO</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Pencatatan aktivitas kebun dan penggunaan bahan kimia secara digital. Export PDF siap cetak untuk audit sertifikasi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="ai-demo" class="py-24 bg-gradient-to-b from-brand-50 to-white">
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

                            <div class="mt-auto border-t pt-2">
                                <div class="h-8 bg-gray-100 rounded-full w-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <span class="text-brand-600 font-bold text-sm uppercase tracking-wider">Teknologi Terbaru</span>
                    <h2 class="text-4xl font-bold text-gray-900 mt-2 mb-6">Asisten Agronomis Pribadi 24 Jam</h2>
                    <p class="text-lg text-gray-600 mb-8">
                        Tidak perlu menunggu penyuluh datang. Dengan Dr. Sawit, Anda memiliki pakar agronomi di saku Anda. Konsultasikan kondisi tanaman kapan saja, di mana saja.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <i class="ph-fill ph-camera"></i>
                            </div>
                            <span class="font-medium text-gray-700">Analisis Visual Instan</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <i class="ph-fill ph-chat-dots"></i>
                            </div>
                            <span class="font-medium text-gray-700">Rekomendasi Penanganan Tepat</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <section id="iot-monitoring" class="py-24 bg-gray-900 text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-20 pointer-events-none">
             <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-brand-600 blur-3xl"></div>
             <div class="absolute top-1/2 right-0 w-64 h-64 rounded-full bg-blue-600 blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16">
                <span class="text-brand-400 font-bold text-sm uppercase tracking-wider">Integrasi Hardware & IoT</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2">Monitoring Lahan & Keamanan 24/7</h2>
                <p class="text-gray-400 mt-4 max-w-2xl mx-auto leading-relaxed">
                    Sistem <b>"Gema Sawit"</b> mendeteksi suara mencurigakan (maling) dan memantau kondisi lingkungan kebun secara realtime melalui sensor pintar.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <div class="bg-gray-800/50 backdrop-blur p-6 rounded-2xl border border-gray-700 hover:border-brand-500 transition group">
                    <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center text-brand-400 mb-4 group-hover:bg-brand-500 group-hover:text-white transition">
                        <i class="ph-fill ph-broadcast text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Sensor Anti-Maling</h4>
                    <p class="text-gray-400 text-sm">Mendeteksi tingkat kebisingan (dB) tinggi di jam rawan dan mengirim alert instan ke pemilik.</p>
                </div>

                <div class="bg-gray-800/50 backdrop-blur p-6 rounded-2xl border border-gray-700 hover:border-brand-500 transition group">
                    <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center text-blue-400 mb-4 group-hover:bg-blue-500 group-hover:text-white transition">
                        <i class="ph-fill ph-globe-hemisphere-west text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Pemetaan Satelit</h4>
                    <p class="text-gray-400 text-sm">Visualisasi lokasi sensor dan kondisi lahan via peta satelit interaktif untuk pemantauan akurat.</p>
                </div>

                <div class="bg-gray-800/50 backdrop-blur p-6 rounded-2xl border border-gray-700 hover:border-brand-500 transition group">
                    <div class="w-12 h-12 bg-gray-700 rounded-xl flex items-center justify-center text-green-400 mb-4 group-hover:bg-green-500 group-hover:text-white transition">
                        <i class="ph-fill ph-telegram-logo text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Notifikasi Telegram</h4>
                    <p class="text-gray-400 text-sm">Laporan kondisi baterai sensor dan peringatan bahaya dikirim langsung ke WhatsApp/Telegram.</p>
                </div>
            </div>
            
            <div class="bg-gray-800 rounded-3xl p-3 border border-gray-700 shadow-2xl transform hover:scale-[1.01] transition duration-500">
                 <div class="bg-gray-900 rounded-2xl overflow-hidden relative border border-gray-800">
                     <div class="h-12 bg-gray-800 border-b border-gray-700 flex items-center px-4 gap-2 justify-between">
                        <div class="flex gap-2">
                             <div class="w-3 h-3 rounded-full bg-red-500"></div>
                             <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                             <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <div class="bg-gray-900 px-4 py-1 rounded text-xs text-gray-500 font-mono">dashboard.smartpalm.id/monitoring</div>
                     </div>
                     
                     <div class="p-8 grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <p class="text-xs text-gray-400 uppercase">Status Sensor</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                <h4 class="text-2xl font-bold text-white">ONLINE</h4>
                            </div>
                        </div>
                        <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <p class="text-xs text-gray-400 uppercase">Kebisingan (Rata-rata)</p>
                            <h4 class="text-2xl font-bold text-white mt-1">45 <span class="text-sm font-normal text-gray-500">dB</span></h4>
                        </div>
                         <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <p class="text-xs text-gray-400 uppercase">Baterai Device</p>
                            <h4 class="text-2xl font-bold text-green-400 mt-1">87%</h4>
                        </div>
                         <div class="bg-gray-800 p-4 rounded-xl border border-gray-700">
                            <p class="text-xs text-gray-400 uppercase">Total Alert</p>
                            <h4 class="text-2xl font-bold text-red-400 mt-1">0 <span class="text-sm font-normal text-gray-500">Hari ini</span></h4>
                        </div>
                        
                        <div class="col-span-full h-48 bg-gray-800 rounded-xl border border-gray-700 flex items-center justify-center relative overflow-hidden">
                            <div class="absolute inset-0 opacity-20" style="background-image: linear-gradient(0deg, transparent 24%, #374151 25%, #374151 26%, transparent 27%, transparent 74%, #374151 75%, #374151 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, #374151 25%, #374151 26%, transparent 27%, transparent 74%, #374151 75%, #374151 76%, transparent 77%, transparent); background-size: 30px 30px;"></div>
                            <div class="text-center z-10">
                                <i class="ph-duotone ph-chart-line-up text-5xl text-brand-600 mb-2"></i>
                                <p class="text-xs text-gray-500 font-mono">REALTIME DATA STREAM VISUALIZATION</p>
                            </div>
                        </div>
                     </div>
                 </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-white border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Siap Meningkatkan Hasil Kebun?</h2>
            <p class="text-gray-600 mb-8 text-lg">
                Bergabunglah dengan transformasi pertanian digital. Kelola kebun lebih mudah, hasil lebih maksimal.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="px-8 py-4 bg-brand-600 text-white font-bold rounded-xl shadow-xl shadow-brand-200 hover:bg-brand-700 hover:-translate-y-1 transition transform duration-200">
                    Masuk Sekarang
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-gray-50 border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="ph-fill ph-plant text-brand-600 text-2xl"></i>
                        <span class="text-xl font-bold text-gray-900">Smart<span class="text-brand-500">Palm</span></span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-xs">
                        Platform manajemen perkebunan kelapa sawit rakyat berbasis teknologi untuk mendukung produktivitas dan keberlanjutan (ISPO).
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-4">Fitur</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-brand-600">Dashboard</a></li>
                        <li><a href="#" class="hover:text-brand-600">Buku Saku GAP</a></li>
                        <li><a href="#" class="hover:text-brand-600">Jadwal & Notifikasi</a></li>
                        <li><a href="#" class="hover:text-brand-600">Dr. Sawit AI</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 mb-4">Hubungi</h4>
                    <ul class="space-y-2 text-sm text-gray-500">
                        <li>Admin KUD Sejahtera</li>
                        <li>support@smartpalm.id</li>
                        <li>Padang, Sumatera Barat</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-200 pt-8 text-center">
                <p class="text-gray-400 text-xs">
                    &copy; {{ date('Y') }} Riset Teknologi Pertanian Modern. Dibuat dengan <i class="ph-fill ph-heart text-red-400"></i> untuk Petani Indonesia.
                </p>
            </div>
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