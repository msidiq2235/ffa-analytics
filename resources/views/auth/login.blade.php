<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Future Football Academy</title>
    <!-- Tambahan Google Font agar teksnya serapi gambar -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tambahan CDN Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F3F5F9] min-h-screen flex items-center justify-center p-4 sm:p-6">

    <div class="max-w-[880px] w-full bg-white rounded-[20px] shadow-[0_8px_30px_rgb(0,0,0,0.08)] flex flex-col md:flex-row overflow-hidden min-h-[520px]">
        
        <!-- Bagian Kiri (Biru) -->
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

        <!-- Bagian Kanan (Form) -->
        <div class="md:w-[58%] p-10 md:px-14 md:py-12 flex flex-col justify-center bg-white">
            <div class="mb-8">
                <h2 class="text-[22px] font-bold text-[#1e293b] mb-1.5 tracking-tight">Selamat Datang</h2>
                <p class="text-[13px] text-[#64748b] font-normal">Silakan masuk menggunakan akun Anda untuk masuk ke sistem.</p>
            </div>

            @if($errors->any())
            <div class="mb-5 p-3 rounded-lg bg-red-50 border border-red-100 text-red-600 text-xs font-medium">
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

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                
                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-[12px] font-bold text-[#334155] mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#94a3b8]">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus 
                            class="w-full pl-10 pr-3.5 py-2.5 bg-white border border-[#e2e8f0] rounded-lg text-sm text-[#334155] focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm"
                            placeholder="nama@email.com">
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-[12px] font-bold text-[#334155]">Password</label>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[12px] font-semibold text-[#4062D6] hover:text-[#263578] transition-colors">Lupa password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#94a3b8]">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input type="password" id="password" name="password" required 
                            class="w-full pl-10 pr-10 py-2.5 bg-white border border-[#e2e8f0] rounded-lg text-sm text-[#334155] focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow placeholder-slate-400 tracking-[0.2em] shadow-sm"
                            placeholder="••••••••">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#94a3b8] hover:text-[#64748b] focus:outline-none transition-colors" onclick="togglePassword()">
                            <i data-lucide="eye" id="eye-icon" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Checkbox Remember Me -->
                <div class="flex items-center pt-1 pb-1.5">
                    <input id="remember_me" type="checkbox" name="remember" 
                        class="w-3.5 h-3.5 text-[#4062D6] bg-white border-[#cbd5e1] rounded focus:ring-[#4062D6] focus:ring-2 cursor-pointer shadow-sm">
                    <label for="remember_me" class="ml-2.5 block text-[12px] font-medium text-[#64748b] cursor-pointer">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full flex justify-center items-center gap-2 px-4 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-semibold rounded-lg transition-all shadow-sm active:scale-[0.98]">
                    <span>Masuk ke Sistem</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Script Toggle Icon Password & Inisialisasi Lucide -->
    <script>
        // Inisialisasi Lucide Icons awal
        lucide.createIcons();

        function togglePassword(){
            const pwd = document.getElementById('password');
            const container = document.getElementById('eye-icon').parentNode;
            
            if(pwd.type === 'password'){
                pwd.type = 'text';
                // Ganti ikon menjadi mata dicoret (eye-off) menggunakan Lucide HTML markup
                container.innerHTML = '<i data-lucide="eye-off" id="eye-icon" class="w-4 h-4"></i>';
            } else {
                pwd.type = 'password';
                // Ganti kembali menjadi ikon mata terbuka (eye)
                container.innerHTML = '<i data-lucide="eye" id="eye-icon" class="w-4 h-4"></i>';
            }
            // Render ulang ikon Lucide setelah elemen DOM diubah via innerHTML
            lucide.createIcons();
        }
    </script>
</body>
</html>