<div>
    <div x-data="{ open: @entangle('deleter') }">
        @include('partials.deleterModal')
    </div>

    <x-cms.page-header title="Infographics" description="Monthly and annual fire-monitoring infographics.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.infographic.create') }}">New infographic</x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center">
        <input type="search" placeholder="Search infographics…" wire:model.live.debounce.300ms="query"
               class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus sm:max-w-xs">
        {{-- Tab kategori: Monthly / Annual / All --}}
        <div class="inline-flex items-center rounded-md border border-line-strong bg-surface p-0.5 sm:ml-auto">
            <button wire:click="$set('category', '')"
                    class="rounded px-3 py-1 text-xs font-medium transition-colors {{ $category === '' ? 'bg-accent text-accent-fg shadow-sm' : 'text-ink-muted hover:bg-hover hover:text-ink' }}">
                All
            </button>
            <button wire:click="$set('category', 'monthly')"
                    class="rounded px-3 py-1 text-xs font-medium transition-colors {{ $category === 'monthly' ? 'bg-accent text-accent-fg shadow-sm' : 'text-ink-muted hover:bg-hover hover:text-ink' }}">
                Monthly
            </button>
            <button wire:click="$set('category', 'annual')"
                    class="rounded px-3 py-1 text-xs font-medium transition-colors {{ $category === 'annual' ? 'bg-accent text-accent-fg shadow-sm' : 'text-ink-muted hover:bg-hover hover:text-ink' }}">
                Annual
            </button>
        </div>
    </div>

    @if ($posts->count())
        <x-cms.data-table>
            <x-slot:head>
                <x-cms.th>Title</x-cms.th>
                <x-cms.th class="hidden md:table-cell">Category</x-cms.th>
                <x-cms.th>Status</x-cms.th>
                <x-cms.th class="w-20 text-right">Actions</x-cms.th>
            </x-slot:head>

            @foreach ($posts as $item)
                <tr class="transition-colors hover:bg-hover/50">
                    <x-cms.td>
                        <div class="flex items-center gap-3">
                            @php $thumb = 'storage/files/photos/' . $item->imgEN; @endphp
                            @if ($item->imgEN && file_exists(public_path($thumb)))
                                <img src="{{ asset($thumb) }}" alt="" class="h-9 w-14 shrink-0 rounded border border-line object-cover">
                            @else
                                <span class="flex h-9 w-14 shrink-0 items-center justify-center rounded border border-line bg-hover text-ink-subtle">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                        <circle cx="9" cy="9" r="2" />
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                    </svg>
                                </span>
                            @endif
                            <div class="min-w-0">
                                <p class="truncate font-medium">
                                    <a href="{{ route('cms.infographic.edit', $item->id) }}">{{ $item->titleEN }}</a>
                                </p>
                                <p class="truncate text-xs text-ink-muted">{{ $item->publishdate }}</p>
                            </div>
                        </div>
                    </x-cms.td>
                    <x-cms.td class="hidden md:table-cell">
                        <x-cms.badge :tone="$item->category === 'monthly' ? 'green' : 'neutral'">
                            {{ $item->category }}
                        </x-cms.badge>
                    </x-cms.td>
                    <x-cms.td>
                        <x-cms.badge :tone="$item->status == 1 ? 'green' : 'amber'">
                            {{ $item->status == 1 ? 'Published' : 'Draft' }}
                        </x-cms.badge>
                    </x-cms.td>
                    <x-cms.td class="text-right">
                        <x-cms.dropdown>
                            <x-cms.dropdown-item href="{{ route('cms.infographic.edit', $item->id) }}">Edit</x-cms.dropdown-item>
                            <x-cms.dropdown-item wire:click="delete({{ $item->id }})" variant="danger">Delete</x-cms.dropdown-item>
                        </x-cms.dropdown>
                    </x-cms.td>
                </tr>
            @endforeach
        </x-cms.data-table>

        {{ $posts->links('cms.pagination') }}
    @else
        <x-cms.empty-state
            title="{{ $query || $category ? 'No matching infographics' : 'No infographics yet' }}"
            description="{{ $query || $category ? 'Try a different search or filter.' : 'Upload the first monthly or annual infographic.' }}">
            @if (! $query && ! $category)
                <x-slot:action>
                    <x-cms.button href="{{ route('cms.infographic.create') }}">New infographic</x-cms.button>
                </x-slot:action>
            @endif
        </x-cms.empty-state>
    @endif
</div>
