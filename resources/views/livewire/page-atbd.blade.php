<div>
    <x-cms.page-header title="ATBD" description="Editable content of the ATBD page, per category.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ url('/id/atbd') }}" target="_blank">View page</x-cms.button>
            <x-cms.button wire:click="storePage" loadingTarget="storePage">Save</x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    <div class="max-w-3xl space-y-4">
        <x-cms.panel title="Details">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    {{-- .live wajib: di Livewire 3 wire:model ditunda, jadi tanpa ini
                         pergantian kategori tidak pernah sampai ke server. --}}
                    <span class="mb-1.5 block text-xs font-medium text-ink">Category</span>
                    <div class="relative">
                        <select wire:model.live="category"
                                class="h-8 w-full appearance-none rounded-md border border-line-strong bg-surface pl-2.5 pr-7 text-sm text-ink cms-focus">
                            <option value="annual">Annual</option>
                            <option value="monthly">Monthly</option>
                        </select>
                        <svg class="pointer-events-none absolute right-2 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-ink-muted"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </div>
                </div>
            </div>
        </x-cms.panel>

        <x-cms.form-tabs>
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

        <div class="mt-8 flex items-center justify-end gap-2 border-t border-line pt-5">
            <x-cms.button wire:click="storePage" loadingTarget="storePage">Save</x-cms.button>
        </div>
    </div>
</div>
