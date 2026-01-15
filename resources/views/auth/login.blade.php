<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Ninja Sawit</title>
    <link rel="icon" href="{{ asset('Images/favicon.svg') }}" type="image/svg+xml">
    <link rel="shortcut icon" href="{{ asset('Images/favicon.svg') }}" type="image/svg+xml">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { brand: { 500: '#10B981', 600: '#059669' } }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen px-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Ninja<span class="text-brand-500">Sawit</span></h1>
            <p class="text-gray-500 text-sm mt-2">Masuk untuk mengelola kebun cerdasmu.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 text-green-600 p-3 rounded-lg mb-4 text-sm font-medium text-center">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 text-red-500 p-3 rounded-lg mb-4 text-sm font-medium text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-200 outline-none transition" 
                       type="email" name="email" placeholder="petani@contoh.com" required>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-200 outline-none transition" 
                       type="password" name="password" placeholder="********" required>
            </div>

            <button class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-brand-200 transition transform active:scale-95" type="submit">
                Masuk Sekarang
            </button>
        </form>
        
        <div class="mt-8 text-center text-sm text-gray-400">
            <p>Lupa password atau belum punya akun?</p>
            <p class="mt-1">Silakan hubungi <span class="font-bold text-gray-600">Admin +62 857 5987 3301</span>.</p>
        </div>
    </div>

</body>
</html>