<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Palm Agronomy</title>
    <link rel="icon" href="{{ asset('Images/favicon.svg') }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('Images/favicon.svg') }}" type="image/svg+xml">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        brand: { 500: '#10B981', 600: '#059669', 900: '#064E3B' }
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity"></div>

    <div class="flex h-screen overflow-hidden">
        
        <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 z-50 transform -translate-x-full transition-transform duration-300 md:relative md:translate-x-0 flex flex-col">
            
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
                <div class="flex items-center">
                    <i class="ph-fill ph-plant text-brand-600 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-800 tracking-tight">Smart<span class="text-brand-500">Palm</span></span>
                </div>
                <button onclick="toggleSidebar()" class="md:hidden text-gray-500 hover:text-red-500">
                    <i class="ph ph-x text-2xl"></i>
                </button>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>
                
                @php 
                    $activeClass = 'bg-brand-50 text-brand-600 font-semibold'; 
                    $inactiveClass = 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'; 
                @endphp

                <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ Request::is('dashboard') ? $activeClass : $inactiveClass }}">
                    <i class="ph ph-squares-four text-xl"></i> <span>Dashboard</span>
                </a>
                
                <a href="{{ url('/dashboard/guides') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ Request::is('dashboard/guides') ? $activeClass : $inactiveClass }}">
                    <i class="ph ph-book-open-text text-xl"></i> <span>Buku Saku GAP</span>
                </a>

                <a href="{{ url('/dashboard/schedules') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ Request::is('dashboard/schedules') ? $activeClass : $inactiveClass }}">
                    <i class="ph ph-calendar-check text-xl"></i> <span>Jadwal & Notif</span>
                </a>

                <a href="{{ url('/dashboard/logbook') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ Request::is('dashboard/logbook') ? $activeClass : $inactiveClass }}">
                    <i class="ph ph-clipboard-text text-xl"></i> <span>Logbook ISPO</span>
                </a>

                <a href="{{ url('/dashboard/harvests') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ Request::is('dashboard/harvests') ? $activeClass : $inactiveClass }}">
                    <i class="ph ph-truck text-xl"></i> <span>Buku Panen</span>
                </a>

                <a href="{{ url('/dashboard/finance') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ Request::is('dashboard/finance') ? $activeClass : $inactiveClass }}">
                    <i class="ph ph-currency-dollar text-xl"></i> <span>Keuangan</span>
                </a>

                <a href="{{ route('monitoring') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ Request::routeIs('monitoring') ? $activeClass : $inactiveClass }}">
                    <i class="ph ph-broadcast text-xl"></i>
                    <span>Monitoring IoT</span>
                    <span class="ml-auto w-2 h-2 rounded-full bg-red-500 animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.6)]"></span>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200 font-medium group">
                        <i class="ph-bold ph-sign-out text-xl group-hover:scale-110 transition-transform"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </nav>

            <div class="p-4 border-t border-gray-100 mt-auto">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center text-brand-600 font-bold">P</div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">Petani Sawit</p>
                        <p class="text-xs text-gray-500">Lahan 2 Hektar</p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-hidden relative w-full">
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 md:hidden flex-shrink-0 z-30">
                <span class="font-bold text-lg text-brand-600 flex items-center gap-2">
                    <i class="ph-fill ph-plant"></i> SmartPalm
                </span>
                <button onclick="toggleSidebar()" class="text-gray-600 hover:text-brand-600 p-2 rounded-lg bg-gray-50">
                    <i class="ph ph-list text-2xl"></i>
                </button>
            </header>

            <div class="flex-1 overflow-y-auto p-4 md:p-10 bg-gray-50/50">
                @yield('content')
            </div>
        </main>
    </div>

    <button onclick="toggleChat()" class="fixed bottom-6 right-6 z-50 group flex items-center gap-3 p-2 bg-gray-900 text-white rounded-full shadow-2xl hover:shadow-brand-500/20 hover:-translate-y-1 transition-all duration-300 border border-gray-800">
        <div class="relative">
            <span class="absolute inset-0 bg-brand-400 rounded-full animate-ping opacity-20"></span>
            <div class="relative w-10 h-10 bg-gradient-to-tr from-brand-500 to-green-400 rounded-full flex items-center justify-center text-white shadow-lg shadow-brand-500/30">
                 <i class="ph-fill ph-sparkle text-xl"></i>
            </div>
        </div>
        <div class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity shadow-sm">
            <i class="ph-bold ph-caret-up text-xs"></i>
        </div>
    </button>

    <div id="chatWindow" class="fixed bottom-24 left-4 right-4 md:left-auto md:right-6 z-40 md:w-[360px] bg-white rounded-3xl shadow-2xl border border-gray-100 hidden flex flex-col overflow-hidden transition-all duration-300 origin-bottom-right scale-95 opacity-0 font-sans h-[60vh] md:h-[550px]">
        
        <div class="bg-white p-4 md:p-5 border-b border-gray-100 flex justify-between items-center relative z-10 shrink-0">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-600 border border-brand-100">
                        <i class="ph-fill ph-plant text-xl"></i>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-base leading-tight">Dr. Sawit AI</h3>
                    <p class="text-xs text-brand-600 font-medium bg-brand-50 px-2 py-0.5 rounded-full inline-block mt-1">
                        Agronomist Expert
                    </p>
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

    <style>
        /* Animasi smooth untuk chat window */
        #chatWindow.hidden { display: none; }
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        // ... (Script logika JS sama persis seperti sebelumnya) ...
        // Copy script JS dari jawaban sebelumnya, atau pakai yang sudah ada.
        // CUMA GANTI bagian "Tampilkan Loading Bubble" biar visualnya senada:
        
        const chatWindow = document.getElementById('chatWindow');
        const messagesArea = document.getElementById('chatMessages');
        const chatForm = document.getElementById('chatForm');
        const sendBtn = document.getElementById('sendBtn');

        function toggleChat() {
            if (chatWindow.classList.contains('hidden')) {
                chatWindow.classList.remove('hidden');
                setTimeout(() => {
                    chatWindow.classList.remove('scale-95', 'opacity-0');
                }, 10);
            } else {
                chatWindow.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    chatWindow.classList.add('hidden');
                }, 300);
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

            // 1. User Message UI
            let userHtml = `
                <div class="flex gap-3 flex-row-reverse animate-fade-in">
                    <div class="bg-gray-900 p-3 rounded-2xl rounded-br-none shadow-md text-sm text-white max-w-[85%]">
                        ${message ? `<p>${message}</p>` : ''}
                        ${image ? `<div class="mt-2 p-1 bg-white/10 rounded-lg"><img src="${URL.createObjectURL(image)}" class="max-w-full rounded-md"></div>` : ''}
                    </div>
                </div>`;
            messagesArea.innerHTML += userHtml;
            messagesArea.scrollTop = messagesArea.scrollHeight;

            // Disable Input
            messageInput.value = '';
            removeImage();
            messageInput.disabled = true;
            sendBtn.disabled = true;
            
            // 2. Loading UI (Keren)
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

            // 3. Send to Backend
            const formData = new FormData();
            formData.append('message', message);
            if (image) formData.append('image', image);
            formData.append('_token', '{{ csrf_token() }}');

            try {
                const response = await fetch('{{ route("chat.ai") }}', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                document.getElementById(loadingId).remove();

                // 4. Bot Response UI
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
    </script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            
            // Toggle Translate (Keluar/Masuk)
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full'); // Munculkan Sidebar
                overlay.classList.remove('hidden'); // Munculkan Overlay
            } else {
                sidebar.classList.add('-translate-x-full'); // Sembunyikan Sidebar
                overlay.classList.add('hidden'); // Sembunyikan Overlay
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1500,
            confirmButtonColor: '#10B981'
        });
    </script>
    @endif
</body>
</html>