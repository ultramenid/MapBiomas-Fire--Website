<div>
    @php
        // Preview: unggahan baru bila ada, kalau tidak gambar lama. File lama
        // diperiksa keberadaannya supaya data sampah tidak jadi gambar rusak.
        $oldCover = 'storage/files/photos/' . $uphoto;
        $coverPreview = $photo
            ? $photo->temporaryUrl()
            : ($uphoto && file_exists(public_path($oldCover)) ? asset($oldCover) : null);
    @endphp

    <x-cms.page-header title="Edit news" description="Update the article or event.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.news.index') }}">Cancel</x-cms.button>
            <x-cms.button wire:click="storePosts" loadingTarget="storePosts">Save</x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    <div class="max-w-3xl space-y-4">
        <x-cms.panel title="Details">
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <span class="mb-1.5 block text-xs font-medium text-ink">Publish date</span>
                    <div wire:ignore x-init="flatpickr('#publishdate', { enableTime: false, dateFormat: 'Y-m-d', disableMobile: 'true' });">
                        <input id="publishdate" type="text" placeholder="YYYY-MM-DD" wire:model="publishdate"
                               class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
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
                <div>
                    <span class="mb-1.5 block text-xs font-medium text-ink">Category</span>
                    <div class="relative">
                        <select wire:model="category"
                                class="h-8 w-full appearance-none rounded-md border border-line-strong bg-surface pl-2.5 pr-7 text-sm text-ink cms-focus">
                            <option value="">Select…</option>
                            <option value="news">News</option>
                            <option value="event">Event</option>
                        </select>
                        <svg class="pointer-events-none absolute right-2 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-ink-muted"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </div>
                </div>
            </div>
        </x-cms.panel>

        <x-cms.panel title="Cover image">
            <x-cms.image-upload model="photo" :previewUrl="$coverPreview"
                                label="Image"
                                hint="Shown as the card thumbnail; a 300×150 preview is generated automatically." />
        </x-cms.panel>

        <x-cms.form-tabs>
            <x-slot:en>
                <div class="space-y-4">
                    <div x-data="{ count: 0 }" x-init="$nextTick(() => count = $refs.countme.value.length)">
                        <label for="titleEN" class="mb-1.5 block text-xs font-medium text-ink">Title</label>
                        <input id="titleEN" maxlength="120" type="text" wire:model="titleEN"
                               x-ref="countme" x-on:keyup="count = $refs.countme.value.length"
                               placeholder="Title…" class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
                        <div class="mt-1 flex justify-end text-xs italic text-ink-subtle">
                            <span x-html="count"></span>&nbsp;/&nbsp;<span x-html="$refs.countme.maxLength"></span>
                        </div>
                    </div>
                    <div x-data="{ count: 0 }" x-init="$nextTick(() => count = $refs.countme.value.length)">
                        <label for="descriptionEN" class="mb-1.5 block text-xs font-medium text-ink">Description</label>
                        <textarea id="descriptionEN" maxlength="255" rows="3" wire:model="descriptionEN"
                                  x-ref="countme" x-on:keyup="count = $refs.countme.value.length"
                                  placeholder="Short description…" class="w-full rounded-md border border-line-strong bg-surface px-2.5 py-2 text-sm text-ink placeholder:text-ink-subtle cms-focus"></textarea>
                        <div class="mt-1 flex justify-end text-xs italic text-ink-subtle">
                            <span x-html="count"></span>&nbsp;/&nbsp;<span x-html="$refs.countme.maxLength"></span>
                        </div>
                    </div>
                    <div>
                        <span class="mb-1.5 block text-xs font-medium text-ink">Content</span>
                        <x-cms.rich-text field="contentEN" :height="480">{{ $contentEN }}</x-cms.rich-text>
                    </div>
                </div>
            </x-slot:en>
            <x-slot:idn>
                <div class="space-y-4">
                    <div x-data="{ count: 0 }" x-init="$nextTick(() => count = $refs.countme.value.length)">
                        <label for="titleID" class="mb-1.5 block text-xs font-medium text-ink">Judul</label>
                        <input id="titleID" maxlength="120" type="text" wire:model="titleID"
                               x-ref="countme" x-on:keyup="count = $refs.countme.value.length"
                               placeholder="Judul…" class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
                        <div class="mt-1 flex justify-end text-xs italic text-ink-subtle">
                            <span x-html="count"></span>&nbsp;/&nbsp;<span x-html="$refs.countme.maxLength"></span>
                        </div>
                    </div>
                    <div x-data="{ count: 0 }" x-init="$nextTick(() => count = $refs.countme.value.length)">
                        <label for="descriptionID" class="mb-1.5 block text-xs font-medium text-ink">Deskripsi</label>
                        <textarea id="descriptionID" maxlength="255" rows="3" wire:model="descriptionID"
                                  x-ref="countme" x-on:keyup="count = $refs.countme.value.length"
                                  placeholder="Deskripsi singkat…" class="w-full rounded-md border border-line-strong bg-surface px-2.5 py-2 text-sm text-ink placeholder:text-ink-subtle cms-focus"></textarea>
                        <div class="mt-1 flex justify-end text-xs italic text-ink-subtle">
                            <span x-html="count"></span>&nbsp;/&nbsp;<span x-html="$refs.countme.maxLength"></span>
                        </div>
                    </div>
                    <div>
                        <span class="mb-1.5 block text-xs font-medium text-ink">Konten</span>
                        <x-cms.rich-text field="contentID" :height="480">{{ $contentID }}</x-cms.rich-text>
                    </div>
                </div>
            </x-slot:idn>
        </x-cms.form-tabs>

        <div class="mt-8 flex items-center justify-end gap-2 border-t border-line pt-5">
            <x-cms.button variant="secondary" href="{{ route('cms.news.index') }}">Cancel</x-cms.button>
            <x-cms.button wire:click="storePosts" loadingTarget="storePosts">Save</x-cms.button>
        </div>
    </div>
</div>
