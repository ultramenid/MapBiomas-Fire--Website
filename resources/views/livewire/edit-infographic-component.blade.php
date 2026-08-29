<div>
    <x-cms.page-header title="Edit infographic" description="Update the monthly or annual infographic.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.infographic.index') }}">Cancel</x-cms.button>
            <x-cms.button wire:click="storePosts" loadingTarget="storePosts">Save</x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    <div class="max-w-3xl space-y-4">
        <x-cms.panel title="Details">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <span class="mb-1.5 block text-xs font-medium text-ink">Publish date</span>
                    <div wire:ignore x-init="flatpickr('#publishdate', { enableTime: false, dateFormat: 'Y-m-d', disableMobile: 'true' });">
                        <input id="publishdate" type="text" placeholder="YYYY-MM-DD" wire:model="publishdate"
                               class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
                    </div>
                </div>
                <div>
                    {{-- Bulan data yang digambarkan, bukan tanggal terbit. Kosongkan
                         untuk memakai bulan publish date. --}}
                    <span class="mb-1.5 block text-xs font-medium text-ink">Data month</span>
                    <input type="month" wire:model="period"
                           class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink cms-focus">
                </div>
                <div>
                    <span class="mb-1.5 block text-xs font-medium text-ink">Category</span>
                    <div class="relative">
                        <select wire:model="category"
                                class="h-8 w-full appearance-none rounded-md border border-line-strong bg-surface pl-2.5 pr-7 text-sm text-ink cms-focus">
                            <option value="monthly">Monthly</option>
                            <option value="annual">Annual</option>
                        </select>
                        <svg class="pointer-events-none absolute right-2 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-ink-muted"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </div>
                </div>
                <div>
                    <span class="mb-1.5 block text-xs font-medium text-ink">Status</span>
                    <div class="relative">
                        <select wire:model="isactive"
                                class="h-8 w-full appearance-none rounded-md border border-line-strong bg-surface pl-2.5 pr-7 text-sm text-ink cms-focus">
                            <option value="1">Publish</option>
                            <option value="0">Draft</option>
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
                <div class="space-y-4">
                    @php
                        // Gambar lama diperiksa keberadaannya supaya data sampah
                        // tidak jatuh ke kotak "gambar rusak".
                        $oldCoverEN = 'storage/files/photos/' . $uphotoEN;
                        $coverPreviewEN = $photoEN
                            ? $photoEN->temporaryUrl()
                            : ($uphotoEN && file_exists(public_path($oldCoverEN)) ? asset($oldCoverEN) : null);
                    @endphp
                    <x-cms.panel title="Image (English)">
                        <x-cms.image-upload model="photoEN" :previewUrl="$coverPreviewEN"
                                            label="Infographic image" hint="Image shown on the English page." />
                    </x-cms.panel>
                    <x-cms.panel title="Text (English)">
                        <div class="space-y-4">
                            <div>
                                <label for="titleEN" class="mb-1.5 block text-xs font-medium text-ink">Title</label>
                                <input id="titleEN" type="text" wire:model="titleEN" placeholder="Title…"
                                       class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
                            </div>
                            <div>
                                <label for="descriptionEN" class="mb-1.5 block text-xs font-medium text-ink">Description</label>
                                <textarea id="descriptionEN" rows="3" wire:model="descriptionEN" placeholder="Short description…"
                                          class="w-full rounded-md border border-line-strong bg-surface px-2.5 py-2 text-sm text-ink placeholder:text-ink-subtle cms-focus"></textarea>
                            </div>
                        </div>
                    </x-cms.panel>
                </div>
            </x-slot:en>
            <x-slot:idn>
                <div class="space-y-4">
                    @php
                        $oldCoverID = 'storage/files/photos/' . $uphotoID;
                        $coverPreviewID = $photoID
                            ? $photoID->temporaryUrl()
                            : ($uphotoID && file_exists(public_path($oldCoverID)) ? asset($oldCoverID) : null);
                    @endphp
                    <x-cms.panel title="Image (Indonesia)">
                        <x-cms.image-upload model="photoID" :previewUrl="$coverPreviewID"
                                            label="Infographic image" hint="Image shown on the Indonesian page." />
                    </x-cms.panel>
                    <x-cms.panel title="Text (Indonesia)">
                        <div class="space-y-4">
                            <div>
                                <label for="titleID" class="mb-1.5 block text-xs font-medium text-ink">Judul</label>
                                <input id="titleID" type="text" wire:model="titleID" placeholder="Judul…"
                                       class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
                            </div>
                            <div>
                                <label for="descriptionID" class="mb-1.5 block text-xs font-medium text-ink">Deskripsi</label>
                                <textarea id="descriptionID" rows="3" wire:model="descriptionID" placeholder="Deskripsi singkat…"
                                          class="w-full rounded-md border border-line-strong bg-surface px-2.5 py-2 text-sm text-ink placeholder:text-ink-subtle cms-focus"></textarea>
                            </div>
                        </div>
                    </x-cms.panel>
                </div>
            </x-slot:idn>
        </x-cms.form-tabs>

        <div class="mt-8 flex items-center justify-end gap-2 border-t border-line pt-5">
            <x-cms.button variant="secondary" href="{{ route('cms.infographic.index') }}">Cancel</x-cms.button>
            <x-cms.button wire:click="storePosts" loadingTarget="storePosts">Save</x-cms.button>
        </div>
    </div>
</div>
