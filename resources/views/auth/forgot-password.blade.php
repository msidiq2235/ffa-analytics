<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Future Football Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tambahkan CDN Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F3F5F9] min-h-screen flex items-center justify-center p-4 sm:p-6">

    <div class="max-w-[880px] w-full bg-white rounded-[20px] shadow-[0_8px_30px_rgb(0,0,0,0.08)] flex flex-col md:flex-row overflow-hidden min-h-[520px]">
        
        <!-- Bagian Kiri (Brand / Info) -->
        <div class="md:w-[42%] bg-[#263578] p-10 flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10 mt-4">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white backdrop-blur-sm">
                        <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    </div>
                    <h1 class="text-[26px] font-bold text-white leading-[1.2] tracking-tight">Future Football<br>Academy</h1>
                </div>
                <p class="text-[12px] text-blue-100/70 leading-[1.8] font-normal pr-4">
                    Platform analitik data sepak bola berstandar profesional. Pemulihan akun dilakukan melalui tautan aman yang dikirimkan ke email terdaftar Anda.
                </p>
            </div>
            <div class="relative z-10 mb-2">
                <p class="text-[10px] text-blue-100/50 font-normal tracking-wide">
                    &copy; 2026 Future Football Academy. All rights reserved.
                </p>
            </div>
        </div>

        <!-- Bagian Kanan (Form Lupa Password) -->
        <div class="md:w-[58%] p-10 md:px-14 md:py-12 flex flex-col justify-center bg-white">
            <div class="mb-8">
                <h2 class="text-[22px] font-bold text-[#1e293b] mb-2 tracking-tight">Lupa Password Anda?</h2>
                <p class="text-[13px] text-[#64748b] font-normal leading-relaxed">
                    Masukkan alamat email akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang password baru.
                </p>
            </div>

            @if (session('status'))
            <div class="mb-6 p-3.5 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 text-[13px] font-medium flex items-center gap-2.5">
                <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0"></i>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-3 rounded-lg bg-red-50 border border-red-100 text-red-600 text-xs font-medium">
                <ul class="list-none space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        <span>{{ $error }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-[12px] font-bold text-[#334155] mb-2">Email Terdaftar</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#94a3b8]">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus 
                            class="w-full pl-10 pr-3.5 py-2.5 bg-white border border-[#e2e8f0] rounded-lg text-sm text-[#334155] focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm placeholder-[#94a3b8]"
                            placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center items-center gap-2 px-4 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-semibold rounded-lg transition-all shadow-sm active:scale-[0.98]">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Kirim Tautan Reset</span>
                    </button>
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-[#4062D6] hover:text-[#263578] transition-colors">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                        <span>Kembali ke halaman login</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Script untuk inisialisasi Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>