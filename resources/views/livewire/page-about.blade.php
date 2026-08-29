<div>
    <x-cms.page-header title="About page" description="Editable rich-text content of the public About page.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ url('/id/about') }}" target="_blank">View page</x-cms.button>
            <x-cms.button wire:click="storePage" loadingTarget="storePage">Save</x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    <x-cms.form-tabs class="max-w-3xl">
        <x-slot:en>
            <x-cms.panel title="Content (English)">
                <x-cms.rich-text field="contentEN" :height="480">{{ $contentEN }}</x-cms.rich-text>
            </x-cms.panel>
        </x-slot:en>
        <x-slot:idn>
            <x-cms.panel title="Konten (Indonesia)">
                <x-cms.rich-text field="contentID" :height="480">{{ $contentID }}</x-cms.rich-text>
            </x-cms.panel>
        </x-slot:idn>
    </x-cms.form-tabs>

    <div class="mt-8 flex max-w-3xl items-center justify-end gap-2 border-t border-line pt-5">
        <x-cms.button wire:click="storePage" loadingTarget="storePage">Save</x-cms.button>
    </div>
</div>
