@props([
    'cancel', // URL of the cancel target
    'save' => 'save', // Livewire action (or "submit" for wire:submit forms)
])

<div {{ $attributes->merge(['class' => 'mt-8 flex items-center justify-end gap-2 border-t border-line pt-5']) }}>
    <x-cms.button variant="secondary" href="{{ $cancel }}">Cancel</x-cms.button>
    @if ($save === 'submit')
        <x-cms.button type="submit" loadingTarget="storeAksi">Save</x-cms.button>
    @else
        <x-cms.button wire:click="{{ $save }}" loadingTarget="{{ $save }}">Save</x-cms.button>
    @endif
</div>
