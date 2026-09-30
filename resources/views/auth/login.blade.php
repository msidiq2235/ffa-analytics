<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Future Football Academy</title>
    <!-- Tambahan Google Font agar teksnya serapi gambar -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                <h1 class="text-[26px] font-bold text-white mb-6 leading-[1.2] tracking-tight">Future Football<br>Academy</h1>
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
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                
                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-[12px] font-bold text-[#334155] mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus 
                        class="w-full px-3.5 py-2.5 bg-white border border-[#e2e8f0] rounded-lg text-sm text-[#334155] focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm"
                        placeholder="">
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
                        <!-- Menambahkan dummy placeholder titik-titik sama seperti di gambar -->
                        <input type="password" id="password" name="password" required 
                            class="w-full px-3.5 py-2.5 bg-white border border-[#e2e8f0] rounded-lg text-sm text-[#334155] focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow pr-10 placeholder-slate-400 tracking-[0.2em] shadow-sm"
                            placeholder="••••••••">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#94a3b8] hover:text-[#64748b] focus:outline-none transition-colors" onclick="togglePassword()">
                            <svg id="eye-icon" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
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
                    Masuk ke Sistem 
                    <!-- Ikon panah kanan kecil yang sama persis -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Script Toggle Icon Password -->
    <script>
        function togglePassword(){
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            
            if(pwd.type === 'password'){
                pwd.type = 'text';
                // Ikon mata dicoret
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                pwd.type = 'password';
                // Ikon mata terbuka
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</body>
</html>