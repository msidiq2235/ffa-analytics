<div class="space-y-6">
    @if($isAssignedCoach && $coachClubs->isEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-800">
            <div class="flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 mt-0.5 shrink-0"></i>
                <div>
                    <h2 class="font-bold text-sm">Belum ada tim yang ditugaskan</h2>
                    <p class="text-xs mt-1">Hubungi administrator agar akun Anda ditetapkan sebagai penanggung jawab tim.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-2 xl:grid-cols-6 gap-4">
        @foreach([
            ['label' => $isAssignedCoach ? 'Tim Ditangani' : 'Total Tim', 'value' => $coachClubs->count(), 'icon' => 'shield', 'color' => 'text-blue-600 bg-blue-50'],
            ['label' => 'Pemain', 'value' => $coachPlayers->count(), 'icon' => 'users', 'color' => 'text-emerald-600 bg-emerald-50'],
            ['label' => 'Jadwal Mendatang', 'value' => $upcomingMatches, 'icon' => 'calendar-clock', 'color' => 'text-orange-600 bg-orange-50'],
            ['label' => 'Pertandingan Selesai', 'value' => $completedMatches, 'icon' => 'circle-check', 'color' => 'text-slate-600 bg-slate-100'],
            ['label' => 'Total Gol', 'value' => $totalGoals, 'icon' => 'goal', 'color' => 'text-rose-600 bg-rose-50'],
            ['label' => 'Total Assist', 'value' => $totalAssists, 'icon' => 'handshake', 'color' => 'text-violet-600 bg-violet-50'],
        ] as $metric)
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                <div class="w-9 h-9 rounded-xl {{ $metric['color'] }} flex items-center justify-center mb-3">
                    <i data-lucide="{{ $metric['icon'] }}" class="w-4 h-4"></i>
                </div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $metric['label'] }}</p>
                <p class="text-2xl font-black text-[#1e293b] mt-1">{{ $metric['value'] }}</p>
            </div>
        @endforeach
    </div>

    @if($liveMatches->isNotEmpty())
        <div class="rounded-2xl bg-rose-600 text-white p-5">
            <div class="flex items-center gap-2 mb-3">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                <h2 class="text-xs font-black uppercase tracking-widest">Pertandingan Berlangsung</h2>
            </div>
            <div class="space-y-2">
                @foreach($liveMatches as $match)
                    <p class="text-sm font-bold">{{ $match->title }} <span class="font-normal opacity-80">vs {{ $match->opponent ?: 'Lawan belum ditentukan' }}</span></p>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">
        <section class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="flex items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="font-bold text-[#1e293b] text-sm">Jadwal Pertandingan</h2>
                    <p class="text-[11px] text-slate-400 mt-1">{{ $isAssignedCoach ? 'Jadwal untuk tim yang Anda tangani' : 'Jadwal seluruh tim' }}</p>
                </div>
                <a href="{{ route('matches.index') }}" class="text-[11px] font-bold text-[#4062D6] hover:text-[#3251b5]">Semua jadwal</a>
            </div>
            <div class="space-y-3">
                @forelse($upcomingMatchesList as $match)
                    <article class="flex items-center gap-4 rounded-xl border border-slate-100 p-3">
                        <div class="w-12 shrink-0 rounded-lg bg-blue-50 text-center py-2">
                            <p class="text-[11px] font-black text-[#4062D6]">{{ $match->match_date->format('d M') }}</p>
                            <p class="text-[10px] font-semibold text-slate-500">{{ $match->match_date->format('H:i') }}</p>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[12px] font-bold text-[#1e293b] truncate">{{ $match->title }} <span class="font-medium text-slate-400">vs {{ $match->opponent ?: 'Lawan belum ditentukan' }}</span></p>
                            <p class="text-[10px] text-slate-500 mt-1">{{ $match->venue ?: 'Lokasi belum ditentukan' }}</p>
                        </div>
                        <span class="rounded-full bg-blue-50 text-[#4062D6] px-2.5 py-1 text-[9px] font-bold">Mendatang</span>
                    </article>
                @empty
                    <p class="rounded-xl bg-slate-50 p-5 text-center text-xs text-slate-500">{{ $isAssignedCoach ? 'Belum ada jadwal mendatang untuk tim Anda.' : 'Belum ada jadwal pertandingan mendatang.' }}</p>
                @endforelse
            </div>

            <div class="border-t border-slate-100 mt-6 pt-5">
                <h3 class="font-bold text-[#1e293b] text-xs mb-3">Hasil Terbaru</h3>
                <div class="space-y-3">
                    @forelse($recentMatchesList as $match)
                        <div class="flex items-center justify-between gap-3 text-xs">
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-700 truncate">{{ $match->title }} vs {{ $match->opponent ?: 'Lawan belum ditentukan' }}</p>
                                <p class="text-[10px] text-slate-400 mt-1">{{ $match->match_date->format('d M Y, H:i') }}</p>
                            </div>
                            <span class="shrink-0 text-[10px] text-slate-500">{{ $match->venue ?: 'Lokasi tidak tersedia' }}</span>
                        </div>
                    @empty
                        <p class="text-[11px] text-slate-400">{{ $isAssignedCoach ? 'Belum ada pertandingan selesai untuk tim Anda.' : 'Belum ada pertandingan selesai.' }}</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="flex items-center justify-between gap-3 mb-5">
                <div>
                    <h2 class="font-bold text-[#1e293b] text-sm">{{ $isAssignedCoach ? 'Tim yang Ditangani' : 'Semua Tim' }}</h2>
                    <p class="text-[11px] text-slate-400 mt-1">Pilih tim untuk melihat profil dan squad</p>
                </div>
                <a href="{{ route('clubs.index') }}" class="text-[11px] font-bold text-[#4062D6]">Profil tim</a>
            </div>
            <div class="space-y-3">
                @forelse($coachClubs as $club)
                    <a href="{{ route('clubs.show', $club) }}" class="flex items-center gap-3 rounded-xl border border-slate-100 p-3 hover:border-blue-200 hover:bg-blue-50/40 transition-colors">
                        @if($club->logo_path)
                            <img src="{{ asset('storage/' . $club->logo_path) }}" alt="" class="w-11 h-11 object-contain rounded-lg bg-slate-50">
                        @else
                            <span class="w-11 h-11 rounded-lg bg-blue-50 text-[#4062D6] font-black flex items-center justify-center">{{ strtoupper(substr($club->name, 0, 2)) }}</span>
                        @endif
                        <span class="min-w-0 flex-1">
                            <span class="block text-xs font-bold text-slate-800 truncate">{{ $club->name }}</span>
                            <span class="block text-[10px] text-slate-400 mt-1">{{ $club->players_count }} pemain</span>
                        </span>
                        <i data-lucide="arrow-up-right" class="w-4 h-4 text-slate-400"></i>
                    </a>
                @empty
                    <p class="rounded-xl bg-slate-50 p-5 text-center text-xs text-slate-500">{{ $isAssignedCoach ? 'Tim yang ditugaskan akan muncul di sini.' : 'Belum ada tim terdaftar.' }}</p>
                @endforelse
            </div>
        </section>
    </div>

    <section class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h2 class="font-bold text-[#1e293b] text-sm">Squad Pemain</h2>
                <p class="text-[11px] text-slate-400 mt-1">{{ $isAssignedCoach ? 'Pemain yang terdaftar pada tim yang Anda tangani' : 'Pemain dari seluruh tim' }}</p>
            </div>
            <div class="flex items-center gap-2">
                <label for="coach-club-filter" class="text-[11px] font-semibold text-slate-500">Filter tim</label>
                <select id="coach-club-filter" class="rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700 focus:border-[#4062D6] focus:outline-none">
                    <option value="all">Semua tim</option>
                    @foreach($coachClubs as $club)
                        <option value="{{ $club->id }}">{{ $club->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="py-3 px-3 text-[9px] font-bold uppercase tracking-widest text-slate-400">Pemain</th>
                        <th class="py-3 px-3 text-[9px] font-bold uppercase tracking-widest text-slate-400">Tim</th>
                        <th class="py-3 px-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 text-center">Posisi</th>
                        <th class="py-3 px-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 text-center">Main</th>
                        <th class="py-3 px-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 text-center">Gol</th>
                        <th class="py-3 px-3 text-[9px] font-bold uppercase tracking-widest text-slate-400 text-center">Assist</th>
                    </tr>
                </thead>
                <tbody id="coach-player-list" class="divide-y divide-slate-50">
                    @forelse($coachPlayers as $player)
                        @php $stats = $coachPlayerStats->get($player->id); @endphp
                        <tr data-coach-player data-club-id="{{ $player->club_id }}" class="hover:bg-slate-50">
                            <td class="py-3 px-3">
                                <span class="block text-xs font-bold text-slate-700">{{ $player->name }}</span>
                                <span class="text-[10px] text-slate-400">#{{ $player->jersey_number ?? '-' }}</span>
                            </td>
                            <td class="py-3 px-3 text-xs text-slate-600">{{ $player->club?->name ?? '-' }}</td>
                            <td class="py-3 px-3 text-xs text-slate-600 text-center">{{ $player->position ?: '-' }}</td>
                            <td class="py-3 px-3 text-xs text-slate-600 text-center">{{ $stats->matches ?? 0 }}</td>
                            <td class="py-3 px-3 text-xs font-bold text-slate-700 text-center">{{ $stats->goals ?? 0 }}</td>
                            <td class="py-3 px-3 text-xs text-slate-600 text-center">{{ $stats->assists ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-slate-400">{{ $isAssignedCoach ? 'Belum ada pemain pada tim yang Anda tangani.' : 'Belum ada pemain terdaftar.' }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <p id="coach-player-empty-filter" class="hidden py-8 text-center text-xs text-slate-400">Tidak ada pemain di tim yang dipilih.</p>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filter = document.getElementById('coach-club-filter');
        const rows = document.querySelectorAll('[data-coach-player]');
        const emptyState = document.getElementById('coach-player-empty-filter');

        if (!filter) return;

        filter.addEventListener('change', function () {
            let visibleCount = 0;
            rows.forEach(function (row) {
                const visible = filter.value === 'all' || row.dataset.clubId === filter.value;
                row.classList.toggle('hidden', !visible);
                if (visible) visibleCount++;
            });
            emptyState.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
        });
    });
</script>
