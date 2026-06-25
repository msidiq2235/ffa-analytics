<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FFA Analytics</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 h-screen flex items-center justify-center">
    <div class="bg-slate-800 p-8 rounded-lg shadow-lg w-full max-w-md border border-slate-700">
        <h2 class="text-2xl font-bold text-emerald-400 text-center mb-6">FFA Analytics</h2>
        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 rounded mb-4 text-sm font-medium">
                {{ $errors->first() }}
            </div>
        @endif
        <form action="{{ url('/') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-slate-300 text-sm font-bold mb-2" for="email">Email</label>
                <input class="w-full px-3 py-2 text-slate-900 rounded focus:outline-none focus:ring-2 focus:ring-emerald-500" id="email" type="email" name="email" required autofocus>
            </div>
            <div class="mb-6">
                <label class="block text-slate-300 text-sm font-bold mb-2" for="password">Password</label>
                <input class="w-full px-3 py-2 text-slate-900 rounded focus:outline-none focus:ring-2 focus:ring-emerald-500" id="password" type="password" name="password" required>
            </div>
            <button class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors" type="submit">
                Login
            </button>
        </form>
    </div>
</body>
</html>