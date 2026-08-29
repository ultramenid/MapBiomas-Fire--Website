@props([
    'name',
    'label' => null,
    'options' => [], // [value => label]
    'hint' => null,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="mb-1.5 block text-xs font-medium text-ink">{{ $label }}</label>
    @endif
    <div class="relative">
        <select id="{{ $name }}" name="{{ $name }}"
                {{ $attributes->merge(['class' => 'h-8 w-full appearance-none rounded-md border border-line-strong bg-surface pl-2.5 pr-7 text-sm text-ink cms-focus']) }}>
            @foreach ($options as $value => $text)
                <option value="{{ $value }}">{{ $text }}</option>
            @endforeach
        </select>
        <svg class="pointer-events-none absolute right-2 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-ink-muted"
             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="m6 9 6 6 6-6" />
        </svg>
    </div>
    @error($name)
        <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
    @enderror
    @if ($hint && ! $errors->has($name))
        <p class="mt-1.5 text-xs text-ink-muted">{{ $hint }}</p>
    @endif
</div>
