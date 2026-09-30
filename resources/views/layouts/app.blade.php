<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'FFA Analytics')</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
body { font-family: 'Inter', sans-serif; }
#sidebar.is-collapsed .sidebar-text { display: none; }
#sidebar.is-collapsed .sidebar-item { justify-content: center; padding-left: 0; padding-right: 0; }
#sidebar.is-collapsed .sidebar-header { padding-left: 0; padding-right: 0; justify-content: center; border-bottom: 1px solid rgba(255,255,255,0.05); }
#sidebar.is-collapsed .sidebar-title { font-size: 0; text-align: center; margin-bottom: 0.5rem; }
#sidebar.is-collapsed .sidebar-title::after { content: "•••"; font-size: 10px; letter-spacing: 2px; color: rgba(191, 219, 254, 0.4); }
#sidebar.is-collapsed .sidebar-profile-container { flex-direction: column; align-items: center; justify-content: center; margin-bottom: 1rem; }
</style>
</head>
<body class="bg-[#F8FAFC] min-h-screen text-slate-800 antialiased">
@php
$realRole = auth()->user()->role ?? 'Super Admin';
$activeRole = session('active_role', $realRole);
@endphp
<div class="flex min-h-screen overflow-hidden">
<aside id="sidebar" class="w-64 bg-[#183262] text-slate-300 flex flex-col shrink-0 min-h-screen z-20 transition-all duration-300 ease-in-out">
<div class="h-20 flex items-center px-6 border-b border-white/10 shrink-0 sidebar-header transition-all">
<div class="flex items-center gap-3 sidebar-item w-full">
<div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
<img src="{{ asset('images/logo-ffa.png') }}" alt="FFA Logo" class="w-7 h-7 object-contain block max-w-full h-auto">
</div>
<div class="flex flex-col sidebar-text overflow-hidden whitespace-nowrap">
<span class="text-sm font-bold text-white leading-tight tracking-wide">FFA Analytics</span>
<span class="text-[9px] font-black text-blue-200/60 tracking-widest uppercase">Pro Suite Platform</span>
</div>
</div>
</div>
<div class="flex-1 overflow-y-auto px-4 py-6 space-y-6 scrollbar-hide">
<div>
<p class="px-3 text-[10px] font-bold text-blue-200/50 uppercase tracking-widest mb-3 sidebar-title whitespace-nowrap transition-all">Menu Utama</p>
<ul class="space-y-1">
<li>
<a href="{{ route('dashboard') }}" title="Dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl sidebar-item transition-all {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white text-xs font-semibold shadow-sm' : 'text-blue-100/70 hover:text-white hover:bg-white/10 text-xs font-medium' }}">
<svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-blue-300' : '' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
<span class="sidebar-text whitespace-nowrap">Dashboard</span>
</a>
</li>
@if($activeRole === 'Super Admin')
<li>
<a href="{{ route('users.index') }}" title="Kelola User" class="flex items-center gap-3 px-3 py-2.5 rounded-xl sidebar-item transition-all {{ request()->routeIs('users.*') ? 'bg-white/15 text-white text-xs font-semibold shadow-sm' : 'text-blue-100/70 hover:text-white hover:bg-white/10 text-xs font-medium' }}">
<svg class="w-4 h-4 {{ request()->routeIs('users.*') ? 'text-blue-300' : '' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
<span class="sidebar-text whitespace-nowrap">Kelola User</span>
</a>
</li>
@endif
</ul>
</div>
@if(in_array($activeRole, ['Super Admin', 'Pelatih', 'Analis']))
<div>
<p class="px-3 text-[10px] font-bold text-blue-200/50 uppercase tracking-widest mb-3 sidebar-title whitespace-nowrap transition-all">Manajemen Tim</p>
<ul class="space-y-1">
<li>
<a href="{{ route('matches.index') }}" title="Jadwal & Match" class="flex items-center gap-3 px-3 py-2.5 rounded-xl sidebar-item transition-all {{ request()->routeIs('matches.*') ? 'bg-white/15 text-white text-xs font-semibold shadow-sm' : 'text-blue-100/70 hover:text-white hover:bg-white/10 text-xs font-medium' }}">
<svg class="w-4 h-4 {{ request()->routeIs('matches.*') ? 'text-blue-300' : '' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
<span class="sidebar-text whitespace-nowrap">Jadwal & Match</span>
</a>
</li>
<li>
<a href="{{ route('clubs.index') }}" title="Profil Klub" class="flex items-center gap-3 px-3 py-2.5 rounded-xl sidebar-item transition-all {{ request()->routeIs('clubs.*') ? 'bg-white/15 text-white text-xs font-semibold shadow-sm' : 'text-blue-100/70 hover:text-white hover:bg-white/10 text-xs font-medium' }}">
<svg class="w-4 h-4 {{ request()->routeIs('clubs.*') ? 'text-blue-300' : '' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
<span class="sidebar-text whitespace-nowrap">Profil Team</span>
</a>
</li>
<li>
<a href="{{ route('players.index') }}" title="Database Pemain" class="flex items-center gap-3 px-3 py-2.5 rounded-xl sidebar-item transition-all {{ request()->routeIs('players.*') ? 'bg-white/15 text-white text-xs font-semibold shadow-sm' : 'text-blue-100/70 hover:text-white hover:bg-white/10 text-xs font-medium' }}">
<svg class="w-4 h-4 {{ request()->routeIs('players.*') ? 'text-blue-300' : '' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
<span class="sidebar-text whitespace-nowrap">Database Pemain</span>
</a>
</li>
</ul>
</div>
@endif
<div>
<p class="px-3 text-[10px] font-bold text-blue-200/50 uppercase tracking-widest mb-3 sidebar-title whitespace-nowrap transition-all">Analitik</p>
<ul class="space-y-1">
<li>
<a href="#" title="Performance" class="flex items-center gap-3 px-3 py-2.5 rounded-xl sidebar-item transition-all text-blue-100/70 hover:text-white hover:bg-white/10 text-xs font-medium">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
<span class="sidebar-text whitespace-nowrap">Performance</span>
</a>
</li>
@if(in_array($activeRole, ['Super Admin', 'Pelatih', 'Analis']))
<li>
<a href="#" title="Export Laporan" class="flex items-center gap-3 px-3 py-2.5 rounded-xl sidebar-item transition-all text-blue-100/70 hover:text-white hover:bg-white/10 text-xs font-medium">
<svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
<span class="sidebar-text whitespace-nowrap">Export Laporan</span>
</a>
</li>
@endif
</ul>
</div>
</div>
<div class="p-4 border-t border-white/10 shrink-0 transition-all">
<div class="flex items-center gap-3 mb-3 sidebar-profile-container sidebar-item">
<div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-xs font-bold text-white relative shrink-0 shadow-sm">
{{ substr(auth()->user()->name ?? 'S', 0, 1) }}
<div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 border-2 border-[#183262] rounded-full"></div>
</div>
<div class="overflow-hidden sidebar-text whitespace-nowrap w-full">
<p class="text-xs font-bold text-white truncate">{{ auth()->user()->name ?? 'Super Admin FFA' }}</p>
<p class="text-[9px] font-bold text-yellow-400 uppercase tracking-widest mt-0.5">{{ $activeRole }}</p>
</div>
</div>
@if($realRole === 'Super Admin')
<div class="mb-3 sidebar-text transition-all">
<form action="{{ route('switch.role') }}" method="POST" id="roleSwitchForm">
@csrf
<div class="relative">
<select name="active_role" onchange="this.form.submit()" class="w-full bg-black/20 hover:bg-black/30 border border-white/10 text-blue-100 text-[10px] font-semibold py-2 pl-3 pr-7 rounded-lg appearance-none focus:outline-none focus:border-blue-500/50 transition-colors cursor-pointer">
<option value="Super Admin" class="text-slate-800" {{ $activeRole == 'Super Admin' ? 'selected' : '' }}>Mode: Super Admin</option>
<option value="Pelatih" class="text-slate-800" {{ $activeRole == 'Pelatih' ? 'selected' : '' }}>Mode: Pelatih</option>
<option value="Analis" class="text-slate-800" {{ $activeRole == 'Analis' ? 'selected' : '' }}>Mode: Analis</option>
<option value="Pemain" class="text-slate-800" {{ $activeRole == 'Pemain' ? 'selected' : '' }}>Mode: Pemain</option>
</select>
<div class="absolute inset-y-0 right-2.5 flex items-center pointer-events-none text-blue-200/50">
<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
</div>
</div>
</form>
</div>
@endif
<div class="space-y-1">
<!-- LINK PENGATURAN AKUN SUDAH DIPERBAIKI -->
<a href="{{ route('profile.index') }}" title="Pengaturan Akun" class="flex items-center gap-2 px-2.5 py-2 rounded-lg transition-colors sidebar-item {{ request()->routeIs('profile.*') ? 'bg-white/15 text-white font-semibold shadow-sm' : 'text-blue-100/70 hover:text-white hover:bg-white/10 font-medium' }} text-[11px]">
<svg class="w-3.5 h-3.5 shrink-0 {{ request()->routeIs('profile.*') ? 'text-blue-300' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
<span class="sidebar-text whitespace-nowrap">Pengaturan Akun</span>
</a>
<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" title="Keluar Sistem" class="w-full flex items-center gap-2 px-2.5 py-2 rounded-lg text-rose-300 hover:text-white hover:bg-rose-500/20 text-[11px] font-medium transition-colors text-left sidebar-item">
<svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
<span class="sidebar-text whitespace-nowrap">Keluar Sistem</span>
</button>
</form>
</div>
</div>
</aside>
<script>
if (localStorage.getItem('sidebar_state') === 'collapsed') {
const sidebar = document.getElementById('sidebar');
sidebar.classList.remove('w-64');
sidebar.classList.add('w-20', 'is-collapsed');
sidebar.classList.remove('transition-all', 'duration-300');
setTimeout(() => { sidebar.classList.add('transition-all', 'duration-300'); }, 50);
}
</script>
<div class="flex-1 flex flex-col h-screen overflow-hidden">
<header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0">
<div class="flex items-center gap-4">
<button onclick="toggleSidebar()" class="text-slate-400 hover:text-slate-600 focus:outline-none transition-transform active:scale-95">
<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
</button>
<div class="flex flex-col">
<h2 class="text-xl font-bold text-slate-800 tracking-tight">@yield('header', 'Dashboard')</h2>
<p class="text-xs text-slate-400 font-medium mt-0.5">@yield('subheader')</p>
</div>
</div>
<div>
@yield('header_action')
</div>
</header>
<main class="flex-1 overflow-y-auto p-8 bg-[#F8FAFC]">
@yield('content')
</main>
</div>
</div>
<script>
function toggleSidebar() {
const sidebar = document.getElementById('sidebar');
sidebar.classList.toggle('w-64');
sidebar.classList.toggle('w-20');
sidebar.classList.toggle('is-collapsed');
if (sidebar.classList.contains('is-collapsed')) {
localStorage.setItem('sidebar_state', 'collapsed');
} else {
localStorage.setItem('sidebar_state', 'expanded');
}
}
</script>
</body>
</html>