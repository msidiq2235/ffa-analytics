@extends('layouts.app')
@section('title', 'Profil Tim - FFA Analytics')
@section('header', 'Profil Tim')
@section('subheader', 'Kelola data klub dan tim Anda di sini. Tambahkan, edit, atau hapus profil tim sesuai kebutuhan.')
@section('content')

@if(session('success'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl mb-6 font-medium text-[13px] flex items-center gap-2.5">
    <i data-lucide="check-circle-2" class="w-4 h-4 shrink-0"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="space-y-6">
    <!-- Action Top Bar -->
    <div class="flex justify-end">
        <a href="{{ route('clubs.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[13px] font-bold rounded-xl transition-colors shadow-sm active:scale-95">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Tambah Tim</span>
        </a>
    </div>

    <!-- Clubs Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        
        @forelse($clubs as $club)
        <div class="bg-white rounded-[24px] border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)] p-6 flex flex-col items-center text-center h-full">
            <!-- Logo Tim -->
            <div class="w-24 h-24 mb-4 flex items-center justify-center">
                @if($club->logo_path)
                    <img src="{{ asset('storage/' . $club->logo_path) }}" alt="Logo {{ $club->name }}" class="w-full h-full object-contain">
                @else
                    <!-- Placeholder jika di database belum ada logo -->
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 font-bold text-2xl uppercase">
                        {{ substr($club->name, 0, 2) }}
                    </div>
                @endif
            </div>

            <!-- Nama Tim -->
            <h3 class="text-xl font-bold text-[#1e293b] mb-2">{{ $club->name }}</h3>
            <p class="text-[12px] text-slate-500 mb-6">
                Penanggung jawab:
                <span class="font-semibold text-slate-700">{{ $club->coach?->name ?? 'Belum ditentukan' }}</span>
            </p>

            <!-- Tombol Aksi -->
            <div class="w-full flex flex-col gap-3 mt-auto">
                <div class="grid grid-cols-2 gap-3">
                    <!-- Tombol Edit Tim -->
                    <a href="{{ route('clubs.edit', $club->id) }}" class="flex items-center justify-center gap-1.5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                        <span>Edit</span>
                    </a>
                    <!-- Tombol Daftar Squad -->
                    <a href="{{ route('clubs.show', $club->id) }}" class="flex items-center justify-center gap-1.5 py-2.5 bg-[#4062D6] hover:bg-[#3251b5] text-white text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        <span>Squad</span>
                    </a>
                </div>
                <!-- Tombol Hapus Tim -->
                <form action="{{ route('clubs.destroy', $club->id) }}" method="POST" class="w-full m-0">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="openDeleteModal(this.form)" class="w-full flex items-center justify-center gap-1.5 py-2.5 bg-white border border-rose-200 hover:bg-rose-50 text-rose-500 text-[12px] font-bold rounded-xl transition-colors shadow-sm">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        <span>Hapus Tim</span>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <!-- State jika belum ada klub -->
        <div class="col-span-full bg-white p-12 rounded-2xl border border-slate-200 text-center flex flex-col items-center justify-center">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-[#4062D6] flex items-center justify-center mb-3">
                <i data-lucide="shield-alert" class="w-6 h-6"></i>
            </div>
            <p class="text-slate-600 font-semibold text-sm mb-1">Belum ada profil tim/klub</p>
            <p class="text-slate-400 text-xs mb-4">Silakan klik tombol "Tambah Klub" di atas untuk mulai mendaftarkan tim.</p>
        </div>
        @endforelse

    </div>
</div>

<!-- Modal Konfirmasi Hapus Kustom -->
<div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs hidden">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xl max-w-md w-full mx-4 p-6 transform transition-all">
        <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 border border-rose-100 flex items-center justify-center mb-4 mx-auto">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
        </div>
        <h3 class="text-base font-bold text-slate-800 text-center mb-1">Konfirmasi Penghapusan</h3>
        <p class="text-xs text-slate-500 text-center mb-6 leading-relaxed">Apakah Anda yakin ingin menghapus tim ini? Data pemain di dalamnya mungkin terpengaruh dan tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex items-center gap-3">
            <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 rounded-xl text-xs font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors flex items-center justify-center gap-1.5">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
                <span>Batal</span>
            </button>
            <button type="button" id="confirm-delete-btn" class="flex-1 px-4 py-2.5 rounded-xl text-xs font-semibold bg-rose-600 text-white hover:bg-rose-700 transition-colors shadow-sm flex items-center justify-center gap-1.5">
                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                <span>Ya, Hapus</span>
            </button>
        </div>
    </div>
</div>

<!-- Script Lucide Icons & Modal -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    // Inisialisasi Lucide Icons
    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();
    });

    let targetFormToSubmit = null;

    function openDeleteModal(form) {
        targetFormToSubmit = form;
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        targetFormToSubmit = null;
    }

    document.getElementById('confirm-delete-btn').addEventListener('click', function () {
        if (targetFormToSubmit) {
            targetFormToSubmit.submit();
        }
    });
</script>

@endsection