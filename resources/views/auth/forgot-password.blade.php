<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Future Football Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F3F5F9] min-h-screen flex items-center justify-center p-4 sm:p-6">

    <div class="max-w-[880px] w-full bg-white rounded-[20px] shadow-[0_8px_30px_rgb(0,0,0,0.08)] flex flex-col md:flex-row overflow-hidden min-h-[520px]">
        
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

        <div class="md:w-[58%] p-10 md:px-14 md:py-12 flex flex-col justify-center bg-white">
            <div class="mb-8">
                <h2 class="text-[22px] font-bold text-[#1e293b] mb-2 tracking-tight">Lupa Password Anda?</h2>
                <p class="text-[13px] text-[#64748b] font-normal leading-relaxed">
                    Masukkan alamat email akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang password baru.
                </p>
            </div>

            @if (session('status'))
            <div class="mb-6 p-3.5 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 text-[13px] font-medium text-center">
                {{ session('status') }}
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-3 rounded-lg bg-red-50 border border-red-100 text-red-600 text-xs font-medium">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-[12px] font-bold text-[#334155] mb-2">Email Terdaftar</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus 
                        class="w-full px-3.5 py-2.5 bg-white border border-[#e2e8f0] rounded-lg text-sm text-[#334155] focus:outline-none focus:border-[#4062D6] focus:ring-1 focus:ring-[#4062D6] transition-shadow shadow-sm placeholder-[#94a3b8]"
                        placeholder="nama@email.com">
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center items-center px-4 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-semibold rounded-lg transition-all shadow-sm active:scale-[0.98]">
                        Kirim Tautan Reset
                    </button>
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('login') }}" class="text-[12px] font-semibold text-[#4062D6] hover:text-[#263578] transition-colors">
                        Kembali ke halaman login
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>