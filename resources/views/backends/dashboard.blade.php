<x-cms-layout title="Dashboard">
    @php
        // Bar dipakai di beberapa tempat; pembagi dijaga agar tabel kosong
        // tidak memicu division by zero.
        $pct = fn ($value, $total) => $total > 0 ? round($value / $total * 100) : 0;
        $trendMax = $trend->max('total') ?: 1;
    @endphp

    <x-cms.page-header title="Dashboard" description="Overview of MapBiomas Fire content.">
        <x-slot:actions>
            <span class="text-xs text-ink-muted">{{ \Carbon\Carbon::now('Asia/Jakarta')->format('d M Y') }}</span>
        </x-slot:actions>
    </x-cms.page-header>

    {{-- Ringkasan jumlah konten --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        @foreach ($stats as $stat)
            <x-cms.stat-card :label="$stat['label']" :value="$stat['value']" :href="$stat['url']">
                <p class="text-xs text-ink-muted">{{ $stat['note'] }}</p>
            </x-cms.stat-card>
        @endforeach
    </div>

    <div class="mt-6 grid gap-4 lg:grid-cols-2">
        {{-- Tren publikasi 6 bulan terakhir --}}
        <x-cms.panel title="News published" description="Recent months">
            <div class="grid grid-cols-6 gap-2 pt-2">
                @forelse ($trend as $month)
                    <div>
                        <p class="text-center text-xs text-ink-muted">{{ $month['total'] }}</p>
                        {{-- Tinggi bar persen, jadi tracknya harus tinggi tetap. --}}
                        <div class="flex h-28 items-end">
                            <div class="w-full rounded bg-accent" style="height: {{ max($pct($month['total'], $trendMax), 2) }}%"></div>
                        </div>
                        <p class="pt-1 text-center text-xs text-ink-muted">{{ $month['label'] }}</p>
                    </div>
                @empty
                    <p class="text-sm text-ink-muted">No news yet</p>
                @endforelse
            </div>
        </x-cms.panel>

        {{-- Komposisi kategori --}}
        <x-cms.panel title="Content breakdown">
            @php
                // Dua tabel berbeda, jadi persentasenya dihitung per grup
                // supaya kategori 'monthly' tidak diadu dengan 'news'.
                $groups = collect(['News & events' => $newsByCategory, 'Factsheets' => $factsheetByCategory])
                    ->filter(fn ($rows) => count($rows));
            @endphp
            <div class="pt-1">
                @forelse ($groups as $groupLabel => $rows)
                    @php $groupTotal = $rows->sum('total'); @endphp
                    <p class="pt-2 text-xs text-ink-muted">{{ $groupLabel }} · {{ $groupTotal }}</p>
                    @foreach ($rows as $row)
                        <div class="py-1">
                            <div class="flex justify-between text-sm text-ink">
                                <span>{{ $row->category ?: 'uncategorized' }}</span>
                                <span>{{ $row->total }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-hover">
                                <div class="h-2 rounded-full bg-accent/70" style="width: {{ $pct($row->total, $groupTotal) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @empty
                    <p class="text-sm text-ink-muted">No content yet</p>
                @endforelse
            </div>
        </x-cms.panel>
    </div>

    {{-- Aktivitas terakhir --}}
    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        <x-cms.panel title="Latest news">
            <ul class="divide-y divide-line">
                @forelse ($recentNews as $item)
                    <li>
                        <a href="{{ route('cms.news.edit', $item->id) }}"
                           class="flex items-center justify-between gap-4 py-2.5 text-sm text-ink transition-colors first:pt-0 last:pb-0 hover:text-accent">
                            <span class="min-w-0 truncate">{{ $item->titleID }}</span>
                            <span class="flex items-center gap-2 whitespace-nowrap text-xs text-ink-muted">
                                {{ $item->publishdate }}
                                <x-cms.badge :tone="$item->status == 1 ? 'green' : 'amber'">
                                    {{ $item->status == 1 ? 'Published' : 'Draft' }}
                                </x-cms.badge>
                            </span>
                        </a>
                    </li>
                @empty
                    <p class="py-2 text-sm text-ink-muted">No news yet</p>
                @endforelse
            </ul>
        </x-cms.panel>

        <x-cms.panel title="Latest infographics">
            <ul class="divide-y divide-line">
                @forelse ($recentInfographic as $item)
                    <li>
                        <a href="{{ route('cms.infographic.edit', $item->id) }}"
                           class="flex items-center justify-between gap-4 py-2.5 text-sm text-ink transition-colors first:pt-0 last:pb-0 hover:text-accent">
                            <span class="min-w-0 truncate">{{ $item->titleEN }}</span>
                            <span class="flex items-center gap-2 whitespace-nowrap text-xs text-ink-muted">
                                {{ $item->publishdate }}
                                <x-cms.badge :tone="$item->status == 1 ? 'green' : 'amber'">
                                    {{ $item->status == 1 ? 'Published' : 'Draft' }}
                                </x-cms.badge>
                            </span>
                        </a>
                    </li>
                @empty
                    <p class="py-2 text-sm text-ink-muted">No infographics yet</p>
                @endforelse
            </ul>
        </x-cms.panel>
    </div>
</x-cms-layout>
