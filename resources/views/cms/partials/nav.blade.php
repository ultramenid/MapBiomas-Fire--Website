@php
$navGroups = [
    [
        'label' => 'Overview',
        'items' => [
            ['route' => 'cms.dashboard', 'match' => 'cms.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z'],
        ],
    ],
    [
        'label' => 'Content',
        'items' => [
            ['route' => 'cms.news.index', 'match' => 'cms.news.*', 'label' => 'News', 'icon' => 'M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-4 0V9M18 14h-8M15 18h-5M10 6h8v4h-8V6Z'],
            ['route' => 'cms.infographic.index', 'match' => 'cms.infographic.*', 'label' => 'Infographics', 'icon' => 'M3 3v18h18M7 15l4-6 3 4 5-8'],
            ['route' => 'cms.factsheet.index', 'match' => 'cms.factsheet.*', 'label' => 'Factsheets', 'icon' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM14 2v6h6M12 18v-6M9 15l3 3 3-3'],
            ['route' => 'cms.faq.index', 'match' => 'cms.faq.*', 'label' => 'FAQ', 'icon' => 'M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20zM9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01'],
        ],
    ],
    [
        'label' => 'Pages',
        'items' => [
            ['route' => 'cms.pages.about', 'match' => 'cms.pages.about', 'label' => 'About', 'icon' => 'M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20zM12 16v-4M12 8h.01'],
            ['route' => 'cms.pages.termofuse', 'match' => 'cms.pages.termofuse', 'label' => 'Terms of Use', 'icon' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'],
            ['route' => 'cms.pages.atbd', 'match' => 'cms.pages.atbd', 'label' => 'ATBD', 'icon' => 'M4 19.5A2.5 2.5 0 0 1 6.5 17H20M4 19.5A2.5 2.5 0 0 0 6.5 22H20V2H6.5A2.5 2.5 0 0 0 4 4.5v15z'],
            ['route' => 'cms.pages.refrencemap', 'match' => 'cms.pages.refrencemap', 'label' => 'Reference Map', 'icon' => 'M1 6v16l7-4 8 4 7-4V2l-7 4-8-4-7 4zM8 2v16M16 6v16'],
            ['route' => 'cms.pages.downloads', 'match' => 'cms.pages.downloads', 'label' => 'Downloads', 'icon' => 'M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3'],
        ],
    ],
];
@endphp

@foreach ($navGroups as $group)
    <div>
        <p class="px-2 pb-1.5 text-[11px] font-semibold uppercase tracking-widest text-ink-subtle">{{ $group['label'] }}</p>
        <div class="space-y-0.5">
            @foreach ($group['items'] as $item)
                @php
                    $active = request()->routeIs($item['match']);
                @endphp
                <a href="{{ route($item['route']) }}" aria-current="{{ $active ? 'page' : 'false' }}"
                   class="flex items-center gap-2.5 rounded-md px-2.5 py-1.5 text-sm transition-colors
                          {{ $active ? 'bg-hover font-medium text-ink' : 'text-ink-muted hover:bg-hover hover:text-ink' }}">
                    <svg class="h-4 w-4 shrink-0 {{ $active ? 'text-accent' : 'text-ink-subtle' }}"
                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $item['icon'] }}" />
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>
@endforeach
