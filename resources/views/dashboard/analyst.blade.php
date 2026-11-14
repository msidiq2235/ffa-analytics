<div class="space-y-6">
    <section class="grid grid-cols-2 xl:grid-cols-6 gap-4">
        @foreach([
            ['label' => 'Sedang Live', 'value' => $analystLiveCount, 'icon' => 'radio', 'tone' => 'text-rose-600 bg-rose-50'],
            ['label' => 'Akan Datang', 'value' => $analystUpcomingCount, 'icon' => 'calendar-clock', 'tone' => 'text-blue-600 bg-blue-50'],
            ['label' => 'Selesai', 'value' => $analystCompletedCount, 'icon' => 'circle-check', 'tone' => 'text-emerald-600 bg-emerald-50'],
            ['label' => 'Gol Dicatat', 'value' => $analystGoals, 'icon' => 'goal', 'tone' => 'text-orange-600 bg-orange-50'],
            ['label' => 'Assist Dicatat', 'value' => $analystAssists, 'icon' => 'handshake', 'tone' => 'text-violet-600 bg-violet-50'],
            ['label' => 'Aksi Analitik', 'value' => $analystEntries + $analystVectors, 'icon' => 'activity', 'tone' => 'text-cyan-600 bg-cyan-50'],
        ] as $metric)
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                <div class="flex items-start justify-between gap-2">
                    <div class="w-9 h-9 rounded-xl {{ $metric['tone'] }} flex items-center justify-center">
                        <i data-lucide="{{ $metric['icon'] }}" class="w-4 h-4"></i>
                    </div>
                    <span class="text-2xl font-black text-[#1e293b]">{{ $metric['value'] }}</span>
                </div>
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-3">{{ $metric['label'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 md:p-7">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
                <div>
                    <h2 class="font-bold text-base text-[#1e293b]">Pusat Pertandingan</h2>
                    <p class="text-xs text-slate-500 mt-1">Cari pertandingan, pilih status, lalu lanjutkan analisis.</p>
                </div>
                <label class="relative block w-full md:w-64">
                    <i data-lucide="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                    <input id="analyst-match-search" type="search" placeholder="Cari tim atau lawan..." class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-3 text-sm focus:border-[#4062D6] focus:outline-none focus:ring-1 focus:ring-[#4062D6]">
                </label>
            </div>

            <div class="flex flex-wrap gap-2 mb-5" role="group" aria-label="Filter pertandingan">
                @foreach([
                    'all' => 'Semua',
                    'Live' => 'Live',
                    'Upcoming' => 'Mendatang',
                    'Selesai' => 'Selesai',
                ] as $filterValue => $filterLabel)
                    <button type="button" data-analyst-filter="{{ $filterValue }}" aria-pressed="{{ $filterValue === 'all' ? 'true' : 'false' }}" class="rounded-lg border px-4 py-2.5 text-xs font-bold transition-colors {{ $filterValue === 'all' ? 'border-[#4062D6] bg-[#4062D6] text-white' : 'border-slate-200 bg-white text-slate-500 hover:bg-slate-50' }}">
                        {{ $filterLabel }}
                        <span class="ml-1 opacity-70">{{ $filterValue === 'all' ? $analystMatches->count() : $analystMatches->where('status', $filterValue)->count() }}</span>
                    </button>
                @endforeach
            </div>

            <div id="analyst-match-list" class="space-y-3">
                @forelse($analystMatches as $match)
                    @php
                        $matchStats = $analystMatchStats->get($match->id);
                        $statusTone = $match->status === 'Live'
                            ? 'bg-rose-50 border-rose-100 text-rose-600'
                            : ($match->status === 'Selesai' ? 'bg-emerald-50 border-emerald-100 text-emerald-600' : 'bg-amber-50 border-amber-100 text-amber-700');
                        $matchSearch = strtolower($match->title . ' ' . ($match->opponent ?? ''));
                    @endphp
                    <article data-analyst-match data-status="{{ $match->status }}" data-search="{{ $matchSearch }}" class="rounded-xl border border-slate-100 p-5 hover:border-blue-200 hover:bg-blue-50/20 transition-colors">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-14 h-14 shrink-0 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center">
                                    <span class="text-xs font-black text-[#4062D6]">{{ $match->match_date->format('d M') }}</span>
                                    <span class="text-[11px] font-semibold text-slate-500">{{ $match->match_date->format('H:i') }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-[#1e293b] truncate">{{ $match->title }} <span class="font-medium text-slate-500">vs {{ $match->opponent ?: 'Lawan belum ditentukan' }}</span></p>
                                    <p class="text-xs text-slate-500 mt-1.5 truncate">{{ $match->venue ?: 'Lokasi belum ditentukan' }} <span class="mx-1 text-slate-300">·</span> Formasi {{ $match->formation ?: '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between lg:justify-end gap-3">
                                <span class="rounded-full border px-3 py-1.5 text-[10px] font-black uppercase tracking-wide {{ $statusTone }}">{{ $match->status ?: 'Upcoming' }}</span>
                                <div class="flex items-center gap-2 text-center">
                                    <span class="min-w-11 rounded-lg bg-orange-50 px-2.5 py-1.5 text-xs font-black text-orange-600" title="Gol">{{ $matchStats->goals ?? 0 }} G</span>
                                    <span class="min-w-11 rounded-lg bg-violet-50 px-2.5 py-1.5 text-xs font-black text-violet-600" title="Assist">{{ $matchStats->assists ?? 0 }} A</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 mt-3 pt-3">
                            <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                                <span>{{ $matchStats->entries ?? 0 }} catatan statistik</span>
                                <span>{{ $matchStats->shots ?? 0 }} tembakan</span>
                                <span>{{ $matchStats->passes ?? 0 }} umpan sukses</span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @if($match->status === 'Live')
                                    <a href="{{ route('matches.live', $match) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-[#4062D6] px-3.5 py-2 text-xs font-bold text-white hover:bg-[#3251b5]"><i data-lucide="radio" class="w-3.5 h-3.5"></i> Live Analytics</a>
                                @endif
                                <a href="{{ route('matches.vector', $match) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-indigo-100 px-3.5 py-2 text-xs font-bold text-indigo-600 hover:bg-indigo-50"><i data-lucide="move-up-right" class="w-3.5 h-3.5"></i> Vektor</a>
                                <a href="{{ route('reports.match.show', $match) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 px-3.5 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> Laporan</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-200 p-8 text-center">
                        <i data-lucide="calendar-x" class="w-7 h-7 mx-auto text-slate-300"></i>
                        <p class="text-xs text-slate-500 mt-2">Belum ada data pertandingan.</p>
                    </div>
                @endforelse
                <p id="analyst-match-empty" class="hidden rounded-xl bg-slate-50 p-6 text-center text-xs text-slate-500">Tidak ada pertandingan yang cocok dengan filter.</p>
            </div>
        </div>

        <div class="space-y-6">
            <section class="bg-white rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div>
                        <h2 class="font-bold text-base text-[#1e293b]">Tren Gol Dicatat</h2>
                        <p class="text-xs text-slate-500 mt-1">6 bulan terakhir</p>
                    </div>
                    <i data-lucide="chart-no-axes-column-increasing" class="w-4 h-4 text-[#4062D6]"></i>
                </div>
                <div class="flex h-44 items-end gap-2 border-b border-slate-100 pb-2">
                    @php $maxMonthlyGoals = max(1, (int) $analystMonthlyGoals->max('goals')); @endphp
                    @foreach($analystMonthlyGoals as $month)
                        <div class="flex h-full flex-1 flex-col items-center justify-end gap-2" title="{{ $month['goals'] }} gol">
                            <span class="text-[11px] font-bold text-slate-600">{{ $month['goals'] }}</span>
                            <div class="w-full max-w-8 rounded-t-md bg-gradient-to-t from-[#4062D6] to-blue-300 transition-all" style="height: {{ max(6, (int) round($month['goals'] / $maxMonthlyGoals * 84)) }}%"></div>
                            <span class="text-[11px] font-semibold text-slate-500">{{ $month['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="bg-white rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-bold text-base text-[#1e293b]">Kontributor Teratas</h2>
                        <p class="text-xs text-slate-500 mt-1">Berdasarkan gol dan assist</p>
                    </div>
                    <i data-lucide="trophy" class="w-4 h-4 text-amber-500"></i>
                </div>
                <div class="space-y-3">
                    @forelse($analystTopPlayers as $index => $player)
                        <a href="{{ route('reports.player.show', $player->id) }}" class="flex items-center gap-3 rounded-xl p-2.5 hover:bg-slate-50 transition-colors">
                            <span class="w-8 h-8 rounded-lg {{ $index === 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-xs font-black">{{ $index + 1 }}</span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-xs font-bold text-slate-700">{{ $player->name }}</span>
                                <span class="block truncate text-[11px] text-slate-500">{{ $player->club_name ?: 'Tanpa tim' }} · #{{ $player->jersey_number ?: '-' }}</span>
                            </span>
                            <span class="text-right">
                                <span class="block text-xs font-black text-[#1e293b]">{{ $player->goals }} G · {{ $player->assists }} A</span>
                                <span class="block text-[11px] text-slate-500">{{ $player->matches }} laga</span>
                            </span>
                        </a>
                    @empty
                        <p class="rounded-xl bg-slate-50 p-4 text-center text-[11px] text-slate-400">Belum ada statistik pemain yang tercatat.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-2xl bg-gradient-to-br from-[#183262] to-[#4062D6] p-6 text-white shadow-md">
                <h2 class="text-lg font-black">Alat Analisis</h2>
                <p class="text-xs text-blue-100/80 mt-1 mb-4">Buka modul untuk mencatat dan mengevaluasi pertandingan.</p>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('matches.index') }}" class="rounded-xl bg-white p-4 text-xs font-bold text-[#183262] hover:bg-slate-100 transition-colors"><i data-lucide="calendar-days" class="w-5 h-5 mb-2"></i><span class="block">Jadwal</span></a>
                    <a href="{{ route('reports.index', ['tab' => 'matches']) }}" class="rounded-xl border border-white/40 p-4 text-xs font-bold text-white hover:bg-white/10 transition-colors"><i data-lucide="chart-column" class="w-5 h-5 mb-2"></i><span class="block">Laporan</span></a>
                </div>
            </section>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = Array.from(document.querySelectorAll('[data-analyst-match]'));
        const filterButtons = document.querySelectorAll('[data-analyst-filter]');
        const search = document.getElementById('analyst-match-search');
        const emptyState = document.getElementById('analyst-match-empty');
        let activeFilter = 'all';

        function updateMatches() {
            const query = search.value.trim().toLowerCase();
            let visibleCount = 0;

            cards.forEach(function (card) {
                const matchesFilter = activeFilter === 'all' || card.dataset.status === activeFilter;
                const matchesSearch = card.dataset.search.includes(query);
                const isVisible = matchesFilter && matchesSearch;
                card.classList.toggle('hidden', !isVisible);
                if (isVisible) visibleCount++;
            });

            emptyState.classList.toggle('hidden', visibleCount > 0 || cards.length === 0);
        }

        filterButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                activeFilter = button.dataset.analystFilter;
                filterButtons.forEach(function (item) {
                    const selected = item === button;
                    item.setAttribute('aria-pressed', selected ? 'true' : 'false');
                    item.classList.toggle('bg-[#4062D6]', selected);
                    item.classList.toggle('border-[#4062D6]', selected);
                    item.classList.toggle('text-white', selected);
                    item.classList.toggle('bg-white', !selected);
                    item.classList.toggle('border-slate-200', !selected);
                    item.classList.toggle('text-slate-500', !selected);
                });
                updateMatches();
            });
        });

        search.addEventListener('input', updateMatches);
    });
</script>
