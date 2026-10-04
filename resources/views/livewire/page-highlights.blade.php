<div>
    <x-cms.page-header title="Highlights" description="The four key numbers on the landing page.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ url('/id') }}" target="_blank">View page</x-cms.button>
            <x-cms.button wire:click="storePage" loadingTarget="storePage">Save</x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    <div class="max-w-3xl space-y-4">
        @foreach ($rows as $i => $row)
            <x-cms.panel title="Highlight {{ $i + 1 }}" wire:key="highlight-{{ $row['id'] }}">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-cms.input name="rows.{{ $i }}.value" label="Value" type="number" step="any" wire:model="rows.{{ $i }}.value" hint="Plain number, e.g. 637011 or 9.5" />
                    <x-cms.input name="rows.{{ $i }}.decimals" label="Decimal places" type="number" min="0" max="2" wire:model="rows.{{ $i }}.decimals" />
                    <x-cms.input name="rows.{{ $i }}.unitEN" label="Unit (English)" wire:model="rows.{{ $i }}.unitEN" hint="e.g. %, ha, Mha" />
                    <x-cms.input name="rows.{{ $i }}.unitID" label="Satuan (Indonesia)" wire:model="rows.{{ $i }}.unitID" hint="mis. %, ha, juta ha" />
                    <x-cms.input name="rows.{{ $i }}.labelEN" label="Label (English)" wire:model="rows.{{ $i }}.labelEN" />
                    <x-cms.input name="rows.{{ $i }}.labelID" label="Label (Indonesia)" wire:model="rows.{{ $i }}.labelID" />
                </div>
            </x-cms.panel>
        @endforeach
    </div>

    <div class="mt-8 flex max-w-3xl items-center justify-end gap-2 border-t border-line pt-5">
        <x-cms.button wire:click="storePage" loadingTarget="storePage">Save</x-cms.button>
    </div>
</div>
