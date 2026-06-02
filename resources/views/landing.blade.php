<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartPalm - Revolusi Digital & IoT Sawit</title>
    <link rel="icon" href="{{ asset('Images/favicon.svg') }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('Images/favicon.svg') }}" type="image/svg+xml">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Syne', 'sans-serif'],
                        body: ['DM Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: { 50: '#ECFDF5', 100: '#D1FAE5', 400: '#34D399', 500: '#10B981', 600: '#059669', 900: '#064E3B' },
                        dark: { 900: '#0A0F0A', 800: '#111811', 700: '#1A2A1A' }
                    },
                }
            }
        }
    </script>

    <style>
        :root {
            --green: #10B981;
            --green-dark: #059669;
            --ink: #0A0F0A;
            --cream: #F7F6F1;
            --muted: #6B7280;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--ink);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .font-display {
            font-family: 'Syne', sans-serif;
        }

        /* Noise texture overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 9999;
            opacity: 0.4;
        }

        /* Nav */
        .nav-pill {
            background: rgba(247, 246, 241, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0,0,0,0.08);
        }

        /* Hero tag */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--ink);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 100px;
        }

        /* Marquee */
        .marquee-track {
            display: flex;
            gap: 48px;
            animation: marquee 20s linear infinite;
            white-space: nowrap;
        }
        @keyframes marquee {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }

        /* Cards */
        .card-hover {
            transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.35s ease;
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 48px rgba(0,0,0,0.10);
        }

        /* Stat counter */
        .stat-line {
            position: relative;
            padding-left: 20px;
        }
        .stat-line::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 32px;
            background: var(--green);
            border-radius: 4px;
        }

        /* Green underline */
        .underline-green {
            position: relative;
            display: inline-block;
        }
        .underline-green::after {
            content: '';
            position: absolute;
            left: 0; bottom: -4px;
            width: 100%; height: 3px;
            background: var(--green);
            border-radius: 2px;
        }

        /* IoT section */
        .iot-dark {
            background: var(--ink);
            position: relative;
        }
        .iot-dark::before {
            content: '';
            position: absolute;
            top: -1px; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--green), transparent);
        }

        /* Terminal mockup */
        .terminal {
            background: #0d1a0d;
            border: 1px solid rgba(16,185,129,0.2);
            border-radius: 16px;
            font-family: 'DM Mono', 'Courier New', monospace;
            font-size: 12px;
        }
        .terminal-dot { width: 10px; height: 10px; border-radius: 50%; }

        /* Sensor card glow */
        .sensor-glow {
            box-shadow: 0 0 0 1px rgba(16,185,129,0.2), 0 0 24px rgba(16,185,129,0.08);
        }

        /* Chat bubble */
        .chat-bubble-ai {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 18px 18px 18px 4px;
        }
        .chat-bubble-user {
            background: var(--ink);
            color: #fff;
            border-radius: 18px 18px 4px 18px;
        }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .anim-fadeup { animation: fadeUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .anim-scalein { animation: scaleIn 0.6s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.4); }
        }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* Animate fade-in for chat */
        .animate-fade-in { animation: fadeUp 0.3s ease-in-out; }

        #chatWindow.hidden { display: none; }
    </style>
</head>
<body>

    <!-- ═══════════════════════ NAVBAR ═══════════════════════ -->
    <nav class="fixed w-full z-50 top-4 px-4 md:px-8" id="navbar">
        <div class="max-w-6xl mx-auto nav-pill rounded-2xl px-5 h-16 flex justify-between items-center shadow-sm">
            <a href="#" class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-brand-500 rounded-lg flex items-center justify-center">
                    <i class="ph-fill ph-plant text-white text-base"></i>
                </div>
                <span class="font-display text-lg font-700 tracking-tight text-gray-900">Smart<span class="text-brand-500">Palm</span></span>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="#ninja-iot" class="text-sm font-medium text-gray-500 hover:text-brand-600 transition flex items-center gap-1.5">
                    <i class="ph-bold ph-broadcast text-brand-500"></i> Ninja IoT
                </a>
                <a href="#fitur" class="text-sm font-medium text-gray-500 hover:text-brand-600 transition">Fitur</a>
                <a href="#ai-demo" class="text-sm font-medium text-gray-500 hover:text-brand-600 transition">AI Demo</a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2 bg-brand-500 text-white text-sm font-semibold rounded-xl hover:bg-brand-600 transition shadow-md shadow-brand-200">
                        Dashboard →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2 bg-gray-900 text-white text-sm font-semibold rounded-xl hover:bg-black transition">
                        Login Petani
                    </a>
                @endauth
            </div>

            <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-gray-600">
                <i class="ph-bold ph-list text-2xl" id="menuIcon"></i>
            </button>
        </div>

        <div id="mobileMenu" class="hidden md:hidden mt-2 bg-white/95 backdrop-blur-md border border-gray-100 rounded-2xl shadow-xl px-5 py-4 flex flex-col gap-3">
            <a href="#ninja-iot" class="text-brand-600 font-semibold py-2 border-b border-gray-50">Ninja Sawit IoT</a>
            <a href="#fitur" class="text-gray-600 font-medium py-2 border-b border-gray-50">Fitur Unggulan</a>
            <a href="#ai-demo" class="text-gray-600 font-medium py-2 border-b border-gray-50">Teknologi AI</a>
            @auth
                <a href="{{ route('dashboard') }}" class="w-full text-center px-5 py-3 bg-brand-500 text-white font-bold rounded-xl">Ke Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="w-full text-center px-5 py-3 bg-gray-900 text-white font-bold rounded-xl">Login Petani</a>
            @endauth
        </div>
    </nav>

    <!-- ═══════════════════════ HERO ═══════════════════════ -->
    <section class="relative min-h-screen flex items-center pt-28 pb-20 overflow-hidden bg-[#F7F6F1]">

        <!-- Background decorations -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] rounded-full bg-brand-100 opacity-40 blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] rounded-full bg-emerald-50 opacity-60 blur-[80px] pointer-events-none"></div>

        <!-- Large background text -->
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none overflow-hidden">
            <span class="font-display text-[18vw] font-800 text-gray-900 opacity-[0.025] leading-none tracking-tighter">SAWIT</span>
        </div>

        <div class="max-w-6xl mx-auto px-6 w-full relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <!-- Left copy -->
                <div>
                    <div class="ms-1 mb-8 anim-fadeup text-gray-900">
                        Dr.Sawit AI + IoT
                    </div>

                    <h1 class="font-display text-5xl md:text-6xl font-800 leading-[1.05] tracking-tight text-gray-900 mb-6 anim-fadeup delay-1">
                        Pantau &amp; Amankan<br>
                        <span class="text-brand-500">Kebun Sawit</span><br>
                        Anda.
                    </h1>

                    <p class="text-lg text-gray-500 leading-relaxed mb-10 max-w-md anim-fadeup delay-2">
                        Gabungan kecerdasan buatan untuk agronomi dan sistem <strong class="text-gray-800">Ninja Sawit IoT</strong> untuk keamanan anti-maling 24 jam. Bertani tenang, hasil maksimal.
                    </p>

                    <div class="flex flex-wrap gap-3 mb-14 anim-fadeup delay-3">
                        <a href="#ninja-iot" class="inline-flex items-center gap-2 px-7 py-3.5 bg-gray-900 text-white font-semibold rounded-xl hover:bg-black transition text-sm shadow-lg shadow-gray-900/20 hover:-translate-y-0.5 transform duration-200">
                            <i class="ph-bold ph-broadcast text-brand-400"></i> Cek Ninja Sawit
                        </a>
                        <a href="https://gapki.id/" target="_blank" class="inline-flex items-center gap-2 px-7 py-3.5 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 hover:border-gray-300 transition text-sm hover:-translate-y-0.5 transform duration-200">
                            Info Industri <i class="ph-bold ph-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="flex gap-10 anim-fadeup delay-4">
                        <div class="stat-line">
                            <p class="font-display text-2xl font-700 text-gray-900">IoT</p>
                            <p class="text-xs text-gray-400 uppercase tracking-widest mt-0.5">Monitoring</p>
                        </div>
                        <div class="stat-line">
                            <p class="font-display text-2xl font-700 text-gray-900">AI</p>
                            <p class="text-xs text-gray-400 uppercase tracking-widest mt-0.5">Diagnosis</p>
                        </div>
                        <div class="stat-line">
                            <p class="font-display text-2xl font-700 text-gray-900">GAP</p>
                            <p class="text-xs text-gray-400 uppercase tracking-widest mt-0.5">Standard</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Hero visual card -->
                <div class="hidden lg:block relative anim-scalein delay-2">
                    <div class="relative">
                        <!-- Main image card -->
                        <div class="rounded-3xl overflow-hidden shadow-2xl border border-white/80">
                            <img src="{{ asset('Images/sawitnih.png') }}" class="w-full h-72 object-cover">
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent p-6">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="w-2.5 h-2.5 bg-brand-400 rounded-full pulse-dot"></span>
                                    <span class="text-white font-display font-600 text-base">Ninja Sawit Aktif</span>
                                </div>
                                <p class="text-white/60 text-xs pl-4">Area Aman &amp; Terpantau</p>
                            </div>
                        </div>

                        <!-- Floating badge cards -->
                        <div class="absolute -top-5 -left-8 bg-white rounded-2xl px-4 py-3 shadow-xl border border-gray-100 flex items-center gap-3">
                            <div class="w-9 h-9 bg-brand-50 rounded-xl flex items-center justify-center text-brand-500">
                                <i class="ph-fill ph-shield-check text-lg"></i>
                            </div>
                            <div>
                                <p class="font-display font-700 text-xs text-gray-900">Keamanan 24/7</p>
                                <p class="text-[10px] text-gray-400">Anti-Pencurian Aktif</p>
                            </div>
                        </div>

                        <div class="absolute -bottom-5 -right-6 bg-white rounded-2xl px-4 py-3 shadow-xl border border-gray-100 flex items-center gap-3">
                            <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center text-brand-500">
                                <i class="ph-fill ph-robot text-lg"></i>
                            </div>
                            <div>
                                <p class="font-display font-700 text-xs text-gray-900">Dr. Sawit AI</p>
                                <p class="text-[10px] text-gray-400">Diagnosis Instan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ MARQUEE ═══════════════════════ -->
    <div class="py-5 bg-brand-500 overflow-hidden border-y border-brand-600">
        <div class="flex overflow-hidden">
            <div class="marquee-track">
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Monitoring Realtime</span>
                <span class="text-white/40">✦</span>
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Diagnosa AI</span>
                <span class="text-white/40">✦</span>
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Pencegahan (IoT) Ninja Sawit</span>
                <span class="text-white/40">✦</span>
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Notifikasi Telegram</span>
                <span class="text-white/40">✦</span>
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Logbook ISPO</span>
                <span class="text-white/40">✦</span>
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Buku Saku GAP</span>
                <span class="text-white/40">✦</span>
                <!-- Duplicate for seamless loop -->
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Monitoring Realtime</span>
                <span class="text-white/40">✦</span>
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Diagnosa AI</span>
                <span class="text-white/40">✦</span>
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Pencegahan (IoT) Ninja Sawit</span>
                <span class="text-white/40">✦</span>
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Notifikasi Telegram</span>
                <span class="text-white/40">✦</span>
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Logbook ISPO</span>
                <span class="text-white/40">✦</span>
                <span class="text-white font-display font-700 text-sm uppercase tracking-widest opacity-80">Buku Saku GAP</span>
                <span class="text-white/40">✦</span>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════ MASALAH ═══════════════════════ -->
    <section id="masalah" class="py-24 bg-[#F7F6F1]">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">

                <div>
                    <span class="text-brand-600 font-semibold text-xs uppercase tracking-widest">Mengapa SmartPalm?</span>
                    <h2 class="font-display text-4xl font-700 text-gray-900 mt-3 mb-5 leading-tight">
                        Keamanan &amp; Produktivitas<br>
                        <span class="underline-green">Jadi Satu.</span>
                    </h2>
                    <p class="text-gray-500 leading-relaxed mb-8">
                        Kami paham bahwa merawat sawit bukan cuma soal pupuk, tapi juga menjaga aset dari pencurian. SmartPalm menggabungkan agronomi digital dengan sistem keamanan IoT Ninja Sawit.
                    </p>
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
                        <p class="text-gray-700 text-sm leading-relaxed italic">
                            "Satu aplikasi untuk diagnosa penyakit, catatan panen, dan alarm anti-maling."
                        </p>
                        <div class="flex items-center gap-2 mt-3">
                            <div class="h-px flex-1 bg-gray-100"></div>
                            <span class="text-xs text-gray-400 font-medium">SmartPalm</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-display font-700 text-gray-900 mb-6 flex items-center gap-2 text-lg">
                        <span class="w-7 h-7 bg-red-50 rounded-lg flex items-center justify-center">
                            <i class="ph-fill ph-warning-circle text-red-500 text-sm"></i>
                        </span>
                        Tantangan Petani Saat Ini
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3 p-3 bg-red-50 rounded-xl">
                            <div class="w-6 h-6 bg-red-100 rounded-lg flex items-center justify-center mt-0.5 shrink-0">
                                <i class="ph-bold ph-siren text-red-500 text-xs"></i>
                            </div>
                            <p class="text-sm text-gray-700 font-medium">Rawan pencurian buah — Ninja Sawit adalah solusinya.</p>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-6 h-6 bg-gray-200 rounded-lg flex items-center justify-center mt-0.5 shrink-0">
                                <i class="ph-bold ph-map-pin text-gray-500 text-xs"></i>
                            </div>
                            <p class="text-sm text-gray-500">Lokasi kebun jauh &amp; sulit dipantau 24 jam.</p>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-6 h-6 bg-gray-200 rounded-lg flex items-center justify-center mt-0.5 shrink-0">
                                <i class="ph-bold ph-plant text-gray-500 text-xs"></i>
                            </div>
                            <p class="text-sm text-gray-500">Tidak tahu jadwal pupuk &amp; dosis yang pas.</p>
                        </div>
                        <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                            <div class="w-6 h-6 bg-gray-200 rounded-lg flex items-center justify-center mt-0.5 shrink-0">
                                <i class="ph-bold ph-virus text-gray-500 text-xs"></i>
                            </div>
                            <p class="text-sm text-gray-500">Penyakit tanaman terlambat dideteksi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ NINJA IoT ═══════════════════════ -->
    <section id="ninja-iot" class="py-28 iot-dark overflow-hidden">

        <!-- Ambient glow -->
        <div class="absolute left-0 top-1/4 w-[500px] h-[500px] rounded-full bg-brand-600 opacity-[0.07] blur-[120px] pointer-events-none"></div>
        <div class="absolute right-0 bottom-1/4 w-[400px] h-[400px] rounded-full bg-blue-600 opacity-[0.06] blur-[100px] pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-6 relative z-10">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div>
                    <span class="inline-flex items-center gap-2 text-brand-400 font-semibold text-xs uppercase tracking-widest mb-4">
                        <span class="w-4 h-px bg-brand-500"></span> Teknologi Andalan
                    </span>
                    <h2 class="font-display text-4xl md:text-5xl font-800 text-white leading-tight tracking-tight">
                        Ninja Sawit<br>IoT System
                    </h2>
                </div>
                <p class="text-gray-400 max-w-sm leading-relaxed text-sm md:text-right">
                    Sistem keamanan &amp; monitoring kebun tercanggih. Deteksi suara mencurigakan dan pantau kondisi lingkungan 24/7.
                </p>
            </div>

            <!-- Feature cards -->
            <div class="grid md:grid-cols-3 gap-5 mb-14">
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 card-hover cursor-default group sensor-glow">
                    <div class="w-12 h-12 bg-brand-500/10 border border-brand-500/20 rounded-xl flex items-center justify-center text-brand-400 mb-5 group-hover:bg-brand-500 group-hover:text-white group-hover:border-brand-500 transition duration-300">
                        <i class="ph-fill ph-broadcast text-2xl"></i>
                    </div>
                    <h4 class="font-display font-700 text-white text-lg mb-2">Sensor Anti-Maling</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Deteksi tingkat kebisingan (dB) tinggi di jam rawan dan kirim alert instan ke pemilik kebun.</p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 card-hover cursor-default group">
                    <div class="w-12 h-12 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-center justify-center text-blue-400 mb-5 group-hover:bg-blue-500 group-hover:text-white group-hover:border-blue-500 transition duration-300">
                        <i class="ph-fill ph-globe-hemisphere-west text-2xl"></i>
                    </div>
                    <h4 class="font-display font-700 text-white text-lg mb-2">Pemetaan Satelit</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Visualisasi lokasi sensor dan kondisi lahan via peta satelit interaktif untuk pemantauan akurat.</p>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 card-hover cursor-default group">
                    <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400 mb-5 group-hover:bg-emerald-500 group-hover:text-white group-hover:border-emerald-500 transition duration-300">
                        <i class="ph-fill ph-telegram-logo text-2xl"></i>
                    </div>
                    <h4 class="font-display font-700 text-white text-lg mb-2">Notifikasi Telegram</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Laporan kondisi baterai &amp; peringatan bahaya dikirim langsung ke WhatsApp/Telegram Anda.</p>
                </div>
            </div>

            <!-- Dashboard mockup -->
            <div class="terminal overflow-hidden">
                <div class="flex items-center justify-between px-5 py-3 border-b border-white/5">
                    <div class="flex gap-2">
                        <div class="terminal-dot bg-red-500/70"></div>
                        <div class="terminal-dot bg-yellow-500/70"></div>
                        <div class="terminal-dot bg-brand-500/70"></div>
                    </div>
                    <div class="text-[10px] text-gray-600 tracking-widest font-mono">dashboard.smartpalm.id/monitoring/ninja-sawit</div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand-500 pulse-dot"></span>
                        <span class="text-[10px] text-brand-500 font-mono">LIVE</span>
                    </div>
                </div>

                <div class="p-6 grid md:grid-cols-4 gap-4">
                    <div class="bg-white/[0.04] border border-white/[0.07] rounded-xl p-4">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-mono mb-2">Status Sensor</p>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-brand-500 pulse-dot"></span>
                            <span class="font-display font-700 text-white text-xl">ONLINE</span>
                        </div>
                    </div>
                    <div class="bg-white/[0.04] border border-white/[0.07] rounded-xl p-4">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-mono mb-2">Kebisingan</p>
                        <p class="font-display font-700 text-white text-xl">45 <span class="text-sm font-normal text-gray-600">dB</span></p>
                    </div>
                    <div class="bg-white/[0.04] border border-white/[0.07] rounded-xl p-4">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-mono mb-2">Baterai</p>
                        <p class="font-display font-700 text-brand-400 text-xl">87%</p>
                    </div>
                    <div class="bg-white/[0.04] border border-white/[0.07] rounded-xl p-4">
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-mono mb-2">Total Alert</p>
                        <p class="font-display font-700 text-gray-400 text-xl">0 <span class="text-xs text-gray-700">hari ini</span></p>
                    </div>

                    <div class="col-span-full bg-white/[0.03] border border-white/[0.06] rounded-xl p-6 flex items-center justify-center h-40 relative overflow-hidden">
                        <!-- Subtle grid -->
                        <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(rgba(16,185,129,0.3) 1px, transparent 1px), linear-gradient(90deg, rgba(16,185,129,0.3) 1px, transparent 1px); background-size: 40px 40px;"></div>
                        <div class="text-center relative z-10">
                            <i class="ph-duotone ph-chart-line-up text-5xl text-brand-600 mb-2"></i>
                            <p class="text-[10px] text-gray-600 font-mono tracking-widest uppercase">Realtime Data Stream Visualization</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ FITUR ═══════════════════════ -->
    <section id="fitur" class="py-24 bg-[#F7F6F1]">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-4">
                <div>
                    <span class="text-brand-600 font-semibold text-xs uppercase tracking-widest">Ekosistem Lengkap</span>
                    <h2 class="font-display text-4xl font-700 text-gray-900 mt-2">Fitur Pendukung</h2>
                </div>
                <p class="text-gray-400 text-sm max-w-xs md:text-right">Lengkap dari hulu ke hilir untuk petani sawit modern Indonesia.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-3xl p-7 border border-gray-100 shadow-sm card-hover">
                    <div class="w-12 h-12 bg-brand-50 rounded-2xl flex items-center justify-center text-brand-600 mb-6">
                        <i class="ph-fill ph-book-open-text text-2xl"></i>
                    </div>
                    <h3 class="font-display font-700 text-lg mb-3 text-gray-900">Buku Saku GAP</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Panduan lengkap budidaya standar sesuai umur tanaman (TBM/TM). Tidak perlu bingung lagi soal dosis dan rotasi.
                    </p>
                    <div class="mt-6 h-px bg-gray-50"></div>
                    <div class="mt-4 flex items-center gap-1.5 text-brand-500 text-xs font-semibold">
                        <span>Selengkapnya</span>
                        <i class="ph-bold ph-arrow-right text-xs"></i>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-3xl p-7 border border-gray-800 shadow-sm card-hover relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-500 opacity-5 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="w-12 h-12 bg-brand-500/10 border border-brand-500/20 rounded-2xl flex items-center justify-center text-brand-400 mb-6">
                        <i class="ph-fill ph-robot text-2xl"></i>
                    </div>
                    <h3 class="font-display font-700 text-lg mb-3 text-white">Dr. Sawit AI</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">
                        Cukup foto daun atau buah, AI kami akan mendiagnosa penyakit dan menentukan kematangan buah dalam hitungan detik.
                    </p>
                    <div class="mt-6 h-px bg-white/5"></div>
                    <div class="mt-4 flex items-center gap-1.5 text-brand-400 text-xs font-semibold">
                        <span>Coba Sekarang</span>
                        <i class="ph-bold ph-arrow-right text-xs"></i>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-7 border border-gray-100 shadow-sm card-hover">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 mb-6">
                        <i class="ph-fill ph-file-text text-2xl"></i>
                    </div>
                    <h3 class="font-display font-700 text-lg mb-3 text-gray-900">Logbook ISPO</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">
                        Pencatatan aktivitas kebun dan penggunaan bahan kimia secara digital. Export PDF siap cetak untuk audit sertifikasi.
                    </p>
                    <div class="mt-6 h-px bg-gray-50"></div>
                    <div class="mt-4 flex items-center gap-1.5 text-orange-500 text-xs font-semibold">
                        <span>Selengkapnya</span>
                        <i class="ph-bold ph-arrow-right text-xs"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ AI DEMO ═══════════════════════ -->
    <section id="ai-demo" class="py-24 bg-white border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-20 items-center">

                <!-- Phone mockup -->
                <div class="relative flex justify-center">
                    <div class="absolute inset-0 bg-brand-100 rounded-full filter blur-3xl opacity-40 transform scale-75 pointer-events-none"></div>
                    <div class="relative w-72">
                        <!-- Phone frame -->
                        <div class="bg-gray-900 rounded-[44px] p-3 shadow-2xl border-4 border-gray-800">
                            <div class="bg-white rounded-[36px] overflow-hidden" style="height: 560px;">

                                <!-- Status bar -->
                                <div class="flex justify-between items-center px-6 pt-4 pb-2">
                                    <span class="text-[10px] font-semibold text-gray-900">9:41</span>
                                    <div class="w-24 h-5 bg-gray-900 rounded-full"></div>
                                    <div class="flex gap-1 items-center">
                                        <i class="ph-bold ph-wifi text-[10px] text-gray-900"></i>
                                        <i class="ph-bold ph-battery-full text-[10px] text-gray-900"></i>
                                    </div>
                                </div>

                                <!-- Chat header -->
                                <div class="flex items-center gap-2.5 px-4 py-3 border-b border-gray-50">
                                    <div class="w-8 h-8 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs font-bold">AI</div>
                                    <div>
                                        <p class="font-display font-700 text-xs text-gray-900">Dr. Sawit</p>
                                        <p class="text-[9px] text-brand-500">● Online</p>
                                    </div>
                                </div>

                                <!-- Messages -->
                                <div class="p-4 flex flex-col gap-3">
                                    <!-- AI -->
                                    <div class="flex gap-2 items-end">
                                        <div class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-white text-[8px] font-bold shrink-0">AI</div>
                                        <div class="chat-bubble-ai px-3 py-2 text-[10px] text-gray-700 max-w-[80%]">
                                            Halo Pak! 👋 Kirim foto daun sawitnya, saya cek kondisinya.
                                        </div>
                                    </div>

                                    <!-- User image -->
                                    <div class="flex gap-2 items-end flex-row-reverse">
                                        <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 text-[8px] font-bold shrink-0">P</div>
                                        <div class="chat-bubble-user p-1 max-w-[65%]">
                                            <img src="{{ asset('Images/ulat.jpeg') }}" class="rounded-2xl w-full">
                                        </div>
                                    </div>

                                    <!-- AI response -->
                                    <div class="flex gap-2 items-end">
                                        <div class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center text-white text-[8px] font-bold shrink-0">AI</div>
                                        <div class="chat-bubble-ai px-3 py-2.5 text-[10px] max-w-[80%]">
                                            <p class="font-bold text-red-500 mb-1 flex items-center gap-1"><i class="ph-fill ph-warning text-xs"></i> Terdeteksi: Ulat Api</p>
                                            <p class="text-gray-600">Gejala serangan <em>Setothosea asigna</em>. Segera lakukan sensus populasi di blok ini.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Phone reflection -->
                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-48 h-6 bg-gray-900/20 blur-xl rounded-full"></div>
                    </div>
                </div>

                <div>
                    <span class="text-brand-600 font-semibold text-xs uppercase tracking-widest">Fitur Cerdas</span>
                    <h2 class="font-display text-4xl font-700 text-gray-900 mt-3 mb-6 leading-tight">
                        Konsultasi AI<br>
                        <span class="underline-green">24 Jam Nonstop.</span>
                    </h2>
                    <p class="text-gray-500 mb-10 leading-relaxed">
                        Dr. Sawit membantu Anda mendiagnosa penyakit tanaman secara instan hanya dari foto. Pelengkap sempurna untuk sistem monitoring Ninja Sawit Anda.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 bg-brand-500 rounded-xl flex items-center justify-center text-white shrink-0 text-sm">
                                <i class="ph-bold ph-camera"></i>
                            </div>
                            <div>
                                <p class="font-display font-700 text-gray-900 text-sm">Foto → Diagnosis Instan</p>
                                <p class="text-gray-400 text-xs mt-0.5">Kirim foto tanaman, AI analisis dalam hitungan detik.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 bg-gray-900 rounded-xl flex items-center justify-center text-white shrink-0 text-sm">
                                <i class="ph-bold ph-chat-circle-text"></i>
                            </div>
                            <div>
                                <p class="font-display font-700 text-gray-900 text-sm">Tanya Jawab Natural</p>
                                <p class="text-gray-400 text-xs mt-0.5">Bertanya layaknya konsultasi dengan pakar sungguhan.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 bg-orange-500 rounded-xl flex items-center justify-center text-white shrink-0 text-sm">
                                <i class="ph-bold ph-lightbulb"></i>
                            </div>
                            <div>
                                <p class="font-display font-700 text-gray-900 text-sm">Solusi Praktis</p>
                                <p class="text-gray-400 text-xs mt-0.5">Rekomendasi penanganan yang bisa langsung diterapkan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════ CTA ═══════════════════════ -->
    <section class="py-24 bg-[#F7F6F1] border-t border-gray-100">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <span class="text-brand-600 font-semibold text-xs uppercase tracking-widest">Mulai Sekarang</span>
            <h2 class="font-display text-4xl md:text-5xl font-800 text-gray-900 mt-3 mb-6 leading-tight tracking-tight">
                Siap Meningkatkan<br>Hasil Kebun?
            </h2>
            <p class="text-gray-400 mb-10 text-lg max-w-md mx-auto">
                Bergabunglah dengan transformasi pertanian digital. Amankan kebun dengan Ninja Sawit sekarang.
            </p>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5 px-10 py-4 bg-gray-900 text-white font-display font-700 rounded-2xl hover:bg-black transition shadow-xl shadow-gray-900/20 hover:-translate-y-1 transform duration-200 text-base">
                Masuk Sekarang
                <i class="ph-bold ph-arrow-right"></i>
            </a>
        </div>
    </section>

    <!-- ═══════════════════════ FOOTER ═══════════════════════ -->
    <footer class="bg-gray-900 border-t border-gray-800 py-10">
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 bg-brand-500 rounded-lg flex items-center justify-center">
                    <i class="ph-fill ph-plant text-white text-sm"></i>
                </div>
                <span class="font-display text-base font-700 text-white">Smart<span class="text-brand-400">Palm</span></span>
            </div>
            <p class="text-xs text-gray-600">
                &copy; {{ date('Y') }} Riset Teknologi Pertanian Modern. Dibuat dengan <i class="ph-fill ph-heart text-red-500/70"></i> untuk Petani Indonesia.
            </p>
            <div class="flex items-center gap-4">
                <a href="#ninja-iot" class="text-xs text-gray-600 hover:text-gray-400 transition">IoT</a>
                <a href="#fitur" class="text-xs text-gray-600 hover:text-gray-400 transition">Fitur</a>
                <a href="#ai-demo" class="text-xs text-gray-600 hover:text-gray-400 transition">AI</a>
            </div>
        </div>
    </footer>

    <!-- ═══════════════════════ CHAT BUTTON ═══════════════════════ -->
    <button onclick="toggleChat()" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 bg-gray-900 text-white rounded-2xl shadow-2xl hover:shadow-brand-500/10 hover:-translate-y-1 transition-all duration-300 border border-gray-800 group">
        <div class="relative">
            <span class="absolute inset-0 bg-brand-400 rounded-full animate-ping opacity-20"></span>
            <div class="relative w-8 h-8 bg-gradient-to-tr from-brand-500 to-emerald-400 rounded-xl flex items-center justify-center text-white shadow-md">
                <i class="ph-fill ph-sparkle text-base"></i>
            </div>
        </div>
        <span class="font-display font-600 text-sm">Dr. Sawit AI</span>
    </button>

    <!-- ═══════════════════════ CHAT WINDOW ═══════════════════════ -->
    <div id="chatWindow" class="fixed bottom-[88px] left-4 right-4 md:left-auto md:right-6 z-40 md:w-[360px] bg-white rounded-3xl shadow-2xl border border-gray-100 hidden flex flex-col overflow-hidden transition-all duration-300 origin-bottom-right scale-95 opacity-0 font-sans" style="height: min(560px, calc(100dvh - 120px))">

        <div class="bg-white p-4 md:p-5 border-b border-gray-100 flex justify-between items-center relative z-10 shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-600 border border-brand-100">
                        <i class="ph-fill ph-plant text-xl"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div>
                    <h3 class="font-display font-700 text-gray-800 text-base leading-tight">Dr. Sawit AI</h3>
                    <p class="text-xs text-brand-600 font-medium bg-brand-50 px-2 py-0.5 rounded-full inline-block mt-1">Agronomist Expert</p>
                </div>
            </div>
            <button onclick="toggleChat()" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>

        <div id="chatMessages" class="flex-1 overflow-y-auto p-4 md:p-5 space-y-4 md:space-y-6 bg-gray-50 scroll-smooth">
            <div class="text-center">
                <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-full uppercase tracking-wider">Hari Ini</span>
            </div>
            <div class="flex gap-3 md:gap-4 items-end">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-green-400 flex items-center justify-center flex-shrink-0 text-white shadow-md shadow-brand-200">
                    <i class="ph-fill ph-sparkle text-sm"></i>
                </div>
                <div class="bg-white p-3 md:p-4 rounded-2xl rounded-bl-none shadow-sm text-sm text-gray-600 border border-gray-100 leading-relaxed max-w-[85%]">
                    <p class="font-semibold text-gray-900 mb-1">Halo, Pak! 👋</p>
                    Saya siap bantu cek kondisi tanaman. Kirim foto buah atau daun sawit, nanti saya diagnosa.
                </div>
            </div>
        </div>

        <div class="p-3 md:p-4 bg-white border-t border-gray-100 relative z-20 shrink-0">
            <div id="imagePreview" class="hidden mb-3 p-2 bg-gray-50 rounded-xl border border-dashed border-gray-300 relative animate-fade-in">
                <div class="flex items-center gap-3">
                    <img id="previewImg" src="" class="h-12 w-12 object-cover rounded-lg shadow-sm">
                    <div class="text-xs text-gray-500">
                        <p class="font-bold text-gray-700">Foto Terlampir</p>
                        <p>Siap dikirim...</p>
                    </div>
                </div>
                <button onclick="removeImage()" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                    <i class="ph-bold ph-trash"></i>
                </button>
            </div>

            <form id="chatForm" onsubmit="sendMessage(event)" class="flex gap-2 items-end">
                @csrf
                <input type="file" id="imageInput" accept="image/*" class="hidden" onchange="previewImage(this)">
                <button type="button" onclick="document.getElementById('imageInput').click()" class="w-10 h-10 md:w-11 md:h-11 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:bg-brand-50 hover:text-brand-600 transition border border-transparent hover:border-brand-200 shrink-0">
                    <i class="ph-bold ph-image text-lg md:text-xl"></i>
                </button>
                <div class="flex-1 relative">
                    <input type="text" id="messageInput" placeholder="Ketik pertanyaan..." class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-4 pr-10 py-2.5 md:py-3 text-sm focus:ring-2 focus:ring-brand-500 focus:bg-white outline-none transition-all placeholder-gray-400">
                </div>
                <button type="submit" id="sendBtn" class="w-10 h-10 md:w-11 md:h-11 flex items-center justify-center rounded-xl bg-gray-900 text-white shadow-lg hover:bg-black hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed shrink-0">
                    <i class="ph-fill ph-paper-plane-right text-lg"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- ═══════════════════════ SCRIPTS ═══════════════════════ -->
    <script>
        const chatWindow = document.getElementById('chatWindow');
        const messagesArea = document.getElementById('chatMessages');
        const sendBtn = document.getElementById('sendBtn');
        let chatHistory = [];
        const limitedHistory = chatHistory.slice(-10);

        function toggleChat() {
            if (chatWindow.classList.contains('hidden')) {
                chatWindow.classList.remove('hidden');
                setTimeout(() => {
                    chatWindow.classList.remove('scale-95', 'opacity-0');
                }, 10);
            } else {
                chatWindow.classList.add('scale-95', 'opacity-0');
                setTimeout(() => { chatWindow.classList.add('hidden'); }, 300);
            }
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            document.getElementById('imageInput').value = '';
            document.getElementById('imagePreview').classList.add('hidden');
        }

        async function sendMessage(e) {
            e.preventDefault();
            const messageInput = document.getElementById('messageInput');
            const imageInput = document.getElementById('imageInput');
            const message = messageInput.value.trim();
            const image = imageInput.files[0];

            if (!message && !image) return;

            let userHtml = `
                <div class="flex gap-3 flex-row-reverse animate-fade-in">
                    <div class="bg-gray-900 p-3 rounded-2xl rounded-br-none shadow-md text-sm text-white max-w-[85%]">
                        ${message ? `<p>${message}</p>` : ''}
                        ${image ? `<div class="mt-2 p-1 bg-white/10 rounded-lg"><img src="${URL.createObjectURL(image)}" class="max-w-full rounded-md"></div>` : ''}
                    </div>
                </div>`;
            messagesArea.innerHTML += userHtml;
            messagesArea.scrollTop = messagesArea.scrollHeight;

            messageInput.value = '';
            removeImage();
            messageInput.disabled = true;
            sendBtn.disabled = true;

            const loadingId = 'loading-' + Date.now();
            messagesArea.innerHTML += `
                <div id="${loadingId}" class="flex gap-4 items-end animate-fade-in">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-green-400 flex items-center justify-center flex-shrink-0 text-white shadow-md shadow-brand-200">
                        <i class="ph-fill ph-sparkle text-sm animate-spin"></i>
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-bl-none shadow-sm border border-gray-100">
                        <div class="flex gap-1.5">
                            <span class="w-2 h-2 bg-brand-400 rounded-full animate-bounce"></span>
                            <span class="w-2 h-2 bg-brand-400 rounded-full animate-bounce delay-100"></span>
                            <span class="w-2 h-2 bg-brand-400 rounded-full animate-bounce delay-200"></span>
                        </div>
                    </div>
                </div>`;
            messagesArea.scrollTop = messagesArea.scrollHeight;

            const formData = new FormData();
            formData.append('message', message);
            if (image) formData.append('image', image);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('history', JSON.stringify(limitedHistory));

            try {
                const response = await fetch('{{ route("chatai") }}', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                document.getElementById(loadingId).remove();
                chatHistory.push({ role: 'user',      content: message });
                chatHistory.push({ role: 'assistant', content: data.reply });

                const botHtml = `
                    <div class="flex gap-4 items-end animate-fade-in">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-500 to-green-400 flex items-center justify-center flex-shrink-0 text-white shadow-md shadow-brand-200">
                            <i class="ph-fill ph-sparkle text-sm"></i>
                        </div>
                        <div class="bg-white p-4 rounded-2xl rounded-bl-none shadow-sm text-sm text-gray-700 border border-gray-100 prose prose-sm max-w-[85%]">
                            ${data.reply}
                        </div>
                    </div>`;
                messagesArea.innerHTML += botHtml;

            } catch (error) {
                document.getElementById(loadingId).remove();
                messagesArea.innerHTML += `<div class="text-center text-xs text-red-500 my-2 bg-red-50 py-1 rounded-lg">Gagal terhubung. Cek koneksi.</div>`;
            } finally {
                messageInput.disabled = false;
                sendBtn.disabled = false;
                messageInput.focus();
                messagesArea.scrollTop = messagesArea.scrollHeight;
            }
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('menuIcon');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.classList.replace('ph-list', 'ph-x');
            } else {
                menu.classList.add('hidden');
                icon.classList.replace('ph-x', 'ph-list');
            }
        }
    </script>
</body>
</html>