<div>
    <div x-data="{ open: @entangle('deleter') }">
        @include('partials.deleterModal')
    </div>

    <x-cms.page-header title="Factsheets" description="Monthly and annual factsheet downloads.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.factsheet.create') }}">New factsheet</x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    @if ($posts->count())
        <x-cms.data-table>
            <x-slot:head>
                <x-cms.th>Title (EN)</x-cms.th>
                <x-cms.th class="hidden md:table-cell">Title (ID)</x-cms.th>
                <x-cms.th>Category</x-cms.th>
                <x-cms.th class="w-20 text-right">Actions</x-cms.th>
            </x-slot:head>

            @foreach ($posts as $item)
                <tr class="transition-colors hover:bg-hover/50">
                    <x-cms.td>
                        <div class="min-w-0">
                            <p class="truncate font-medium">
                                <a href="{{ route('cms.factsheet.edit', $item->id) }}">{{ $item->titleEN }}</a>
                            </p>
                            <p class="truncate text-xs text-ink-muted">{{ \Illuminate\Support\Str::limit(strip_tags($item->descriptionEN), 60) }}</p>
                        </div>
                    </x-cms.td>
                    <x-cms.td class="hidden md:table-cell">
                        <span class="block max-w-xs truncate text-ink-muted">{{ $item->titleID }}</span>
                    </x-cms.td>
                    <x-cms.td>
                        <x-cms.badge :tone="$item->category === 'monthly' ? 'green' : 'neutral'">
                            {{ $item->category }}
                        </x-cms.badge>
                    </x-cms.td>
                    <x-cms.td class="text-right">
                        <x-cms.dropdown>
                            <x-cms.dropdown-item href="{{ route('cms.factsheet.edit', $item->id) }}">Edit</x-cms.dropdown-item>
                            <x-cms.dropdown-item wire:click="delete({{ $item->id }})" variant="danger">Delete</x-cms.dropdown-item>
                        </x-cms.dropdown>
                    </x-cms.td>
                </tr>
            @endforeach
        </x-cms.data-table>

        {{ $posts->links('cms.pagination') }}
    @else
        <x-cms.empty-state title="No factsheets yet"
                           description="Add a monthly or annual factsheet link or PDF.">
            <x-slot:action>
                <x-cms.button href="{{ route('cms.factsheet.create') }}">New factsheet</x-cms.button>
            </x-slot:action>
        </x-cms.empty-state>
    @endif
</div>
