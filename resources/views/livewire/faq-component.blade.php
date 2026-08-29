<div>
    <div x-data="{ open: @entangle('deleter') }">
        @include('partials.deleterModal')
    </div>

    <x-cms.page-header title="FAQ" description="Bilingual frequently asked questions.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.faq.create') }}">New FAQ</x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center">
        <input type="search" placeholder="Search FAQ…" wire:model.live.debounce.300ms="query"
               class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus sm:max-w-xs">
    </div>

    @if ($posts->count())
        <x-cms.data-table>
            <x-slot:head>
                <x-cms.th>Question (ID)</x-cms.th>
                <x-cms.th class="hidden md:table-cell">Question (EN)</x-cms.th>
                <x-cms.th class="w-20 text-right">Actions</x-cms.th>
            </x-slot:head>

            @foreach ($posts as $item)
                <tr class="transition-colors hover:bg-hover/50">
                    <x-cms.td>
                        <span class="block max-w-md truncate font-medium">
                            <a href="{{ route('cms.faq.edit', $item->id) }}">{{ $item->questionID }}</a>
                        </span>
                    </x-cms.td>
                    <x-cms.td class="hidden md:table-cell">
                        <span class="block max-w-md truncate text-ink-muted">{{ $item->questionEN }}</span>
                    </x-cms.td>
                    <x-cms.td class="text-right">
                        <x-cms.dropdown>
                            <x-cms.dropdown-item href="{{ route('cms.faq.edit', $item->id) }}">Edit</x-cms.dropdown-item>
                            <x-cms.dropdown-item wire:click="delete({{ $item->id }})" variant="danger">Delete</x-cms.dropdown-item>
                        </x-cms.dropdown>
                    </x-cms.td>
                </tr>
            @endforeach
        </x-cms.data-table>

        {{ $posts->links('cms.pagination') }}
    @else
        <x-cms.empty-state
            title="{{ $query ? 'No matching FAQ entries' : 'No FAQ entries yet' }}"
            description="{{ $query ? 'Try a different search.' : 'Write the first question and answer.' }}">
            @if (! $query)
                <x-slot:action>
                    <x-cms.button href="{{ route('cms.faq.create') }}">New FAQ</x-cms.button>
                </x-slot:action>
            @endif
        </x-cms.empty-state>
    @endif
</div>
