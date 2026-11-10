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

        <!-- Bagian Kanan (Informasi Reset Password) -->
        <div class="md:w-[58%] p-10 md:px-14 md:py-12 flex flex-col justify-center bg-white">
            <div class="mb-8 text-center md:text-left">
                <h2 class="text-[22px] font-bold text-[#1e293b] mb-3 tracking-tight">Lupa Password Anda?</h2>
            </div>

            <div class="rounded-xl border border-[#dbeafe] bg-[#f8fbff] p-6 text-center md:text-left shadow-sm">
                <div class="flex justify-center md:justify-start mb-4">
                    <div class="w-14 h-14 rounded-full bg-[#e0ecff] flex items-center justify-center text-[#4062D6]">
                        <i data-lucide="shield-alert" class="w-7 h-7"></i>
                    </div>
                </div>
                <p class="text-[15px] font-medium text-[#1e293b] leading-relaxed">
                    Silahkan hubungin admin untuk mereset password
                </p>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-[#4062D6] hover:text-[#263578] transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke halaman login</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Script untuk inisialisasi Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>