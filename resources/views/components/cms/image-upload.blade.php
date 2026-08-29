@props([
    'model', // Livewire property holding the temporary upload
    'label' => null,
    'previewUrl' => null, // current/new image to preview
    'hint' => null,
])

<div>
    @if ($label)
        <span class="mb-1.5 block text-xs font-medium text-ink">{{ $label }}</span>
    @endif
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start">
        <div class="h-36 w-full shrink-0 overflow-hidden rounded-md border border-line bg-hover sm:w-56">
            @if ($previewUrl)
                <img src="{{ $previewUrl }}" alt="Preview" class="h-full w-full object-cover"
                     wire:loading.remove wire:target="{{ $model }}">
            @else
                <div class="flex h-full w-full items-center justify-center text-ink-subtle">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="9" cy="9" r="2" />
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                    </svg>
                </div>
            @endif
            <div wire:loading wire:target="{{ $model }}" class="flex h-full w-full items-center justify-center">
                <svg class="animate-spin h-5 w-5 text-accent" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
        <div class="w-full">
            <input type="file" wire:model="{{ $model }}"
                   class="block w-full cursor-pointer rounded-md border border-line-strong bg-surface text-sm text-ink-muted
                          file:mr-3 file:cursor-pointer file:rounded-md file:border-0 file:bg-hover file:px-3 file:py-1.5
                          file:text-xs file:font-medium file:text-ink hover:file:bg-line cms-focus" />
            @error($model)
                <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
            @enderror
            @if ($hint && ! $errors->has($model))
                <p class="mt-1.5 text-xs text-ink-muted">{{ $hint }}</p>
            @endif
        </div>
    </div>
</div>
