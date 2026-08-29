<div>
    <x-cms.page-header title="New factsheet" description="Add a monthly or annual factsheet link or PDF.">
        <x-slot:actions>
            <x-cms.button variant="secondary" href="{{ route('cms.factsheet.index') }}">Cancel</x-cms.button>
            <x-cms.button wire:click="storeAksi" loadingTarget="storeAksi">Save</x-cms.button>
        </x-slot:actions>
    </x-cms.page-header>

    <div class="max-w-3xl space-y-4">
        <x-cms.panel title="Details">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <span class="mb-1.5 block text-xs font-medium text-ink">Category</span>
                    <div class="relative">
                        <select wire:model="category"
                                class="h-8 w-full appearance-none rounded-md border border-line-strong bg-surface pl-2.5 pr-7 text-sm text-ink cms-focus">
                            <option value="">Select…</option>
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
                <x-cms.panel title="English">
                    <div class="space-y-4">
                        <div>
                            <label for="titleEN" class="mb-1.5 block text-xs font-medium text-ink">Title</label>
                            <input id="titleEN" type="text" wire:model="titleEN" placeholder="Factsheet title…"
                                   class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
                        </div>
                        <div>
                            <label for="descriptionEN" class="mb-1.5 block text-xs font-medium text-ink">Description</label>
                            <textarea id="descriptionEN" rows="3" wire:model="descriptionEN" placeholder="Short description…"
                                      class="w-full rounded-md border border-line-strong bg-surface px-2.5 py-2 text-sm text-ink placeholder:text-ink-subtle cms-focus"></textarea>
                        </div>
                        <div>
                            <label for="linkEN" class="mb-1.5 block text-xs font-medium text-ink">
                                Link <span class="font-normal text-ink-muted">(optional if a PDF is uploaded)</span>
                            </label>
                            <input id="linkEN" type="url" wire:model="linkEN" placeholder="https://… (pdf / download page)"
                                   class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
                        </div>
                        <div>
                            <span class="mb-1.5 block text-xs font-medium text-ink">PDF file</span>
                            <div x-data="{ isUploading: false, progress: 0 }"
                                 x-on:livewire-upload-start="isUploading = true; progress = 0"
                                 x-on:livewire-upload-progress="progress = $event.detail.progress"
                                 x-on:livewire-upload-finish="isUploading = false"
                                 x-on:livewire-upload-error="isUploading = false"
                                 x-on:livewire-upload-cancel="isUploading = false">
                                <input type="file" wire:model="pdfEN" accept="application/pdf"
                                       class="block w-full cursor-pointer rounded-md border border-line-strong bg-surface text-sm text-ink-muted
                                              file:mr-3 file:cursor-pointer file:rounded-md file:border-0 file:bg-hover file:px-3 file:py-1.5
                                              file:text-xs file:font-medium file:text-ink hover:file:bg-line cms-focus">
                                <p class="mt-1.5 text-xs text-ink-muted">
                                    @if ($pdfEN)
                                        Selected: {{ $pdfEN->getClientOriginalName() }}
                                    @else
                                        Click to upload a PDF (max 50MB).
                                    @endif
                                </p>
                                @include('partials.uploadProgress')
                            </div>
                        </div>
                    </div>
                </x-cms.panel>
            </x-slot:en>
            <x-slot:idn>
                <x-cms.panel title="Indonesia">
                    <div class="space-y-4">
                        <div>
                            <label for="titleID" class="mb-1.5 block text-xs font-medium text-ink">Judul</label>
                            <input id="titleID" type="text" wire:model="titleID" placeholder="Judul factsheet…"
                                   class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
                        </div>
                        <div>
                            <label for="descriptionID" class="mb-1.5 block text-xs font-medium text-ink">Deskripsi</label>
                            <textarea id="descriptionID" rows="3" wire:model="descriptionID" placeholder="Deskripsi singkat…"
                                      class="w-full rounded-md border border-line-strong bg-surface px-2.5 py-2 text-sm text-ink placeholder:text-ink-subtle cms-focus"></textarea>
                        </div>
                        <div>
                            <label for="linkID" class="mb-1.5 block text-xs font-medium text-ink">
                                Tautan <span class="font-normal text-ink-muted">(opsional bila PDF diunggah)</span>
                            </label>
                            <input id="linkID" type="url" wire:model="linkID" placeholder="https://… (pdf / halaman unduh)"
                                   class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
                        </div>
                        <div>
                            <span class="mb-1.5 block text-xs font-medium text-ink">Berkas PDF</span>
                            <div x-data="{ isUploading: false, progress: 0 }"
                                 x-on:livewire-upload-start="isUploading = true; progress = 0"
                                 x-on:livewire-upload-progress="progress = $event.detail.progress"
                                 x-on:livewire-upload-finish="isUploading = false"
                                 x-on:livewire-upload-error="isUploading = false"
                                 x-on:livewire-upload-cancel="isUploading = false">
                                <input type="file" wire:model="pdfID" accept="application/pdf"
                                       class="block w-full cursor-pointer rounded-md border border-line-strong bg-surface text-sm text-ink-muted
                                              file:mr-3 file:cursor-pointer file:rounded-md file:border-0 file:bg-hover file:px-3 file:py-1.5
                                              file:text-xs file:font-medium file:text-ink hover:file:bg-line cms-focus">
                                <p class="mt-1.5 text-xs text-ink-muted">
                                    @if ($pdfID)
                                        Dipilih: {{ $pdfID->getClientOriginalName() }}
                                    @else
                                        Klik untuk mengunggah PDF (maks 50MB).
                                    @endif
                                </p>
                                @include('partials.uploadProgress')
                            </div>
                        </div>
                    </div>
                </x-cms.panel>
            </x-slot:idn>
        </x-cms.form-tabs>

        <div class="mt-8 flex items-center justify-end gap-2 border-t border-line pt-5">
            <x-cms.button variant="secondary" href="{{ route('cms.factsheet.index') }}">Cancel</x-cms.button>
            <x-cms.button wire:click="storeAksi" loadingTarget="storeAksi">Save</x-cms.button>
        </div>
    </div>
</div>
