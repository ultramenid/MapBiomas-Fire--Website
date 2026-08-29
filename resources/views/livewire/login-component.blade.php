<div class="w-full max-w-sm">
    <div class="mb-6 flex flex-col items-center">
        <img src="{{ asset('assets/logo-fire.png') }}" alt="MapBiomas Fire" class="h-12 w-auto">
        <p class="mt-4 text-xs font-semibold uppercase tracking-widest text-ink-muted">CMS</p>
    </div>

    <div class="rounded-lg border border-line bg-surface p-6 shadow-sm">
        <h1 class="text-base font-semibold text-ink">Log in to your account</h1>
        <p class="mt-1 text-sm text-ink-muted">Enter your credentials to access the CMS.</p>

        <form wire:submit.prevent="login" class="mt-5 space-y-4">
            @csrf
            <div>
                <label for="email" class="mb-1.5 block text-xs font-medium text-ink">Email</label>
                <input type="text" id="email" autofocus autocomplete="email" wire:model="email"
                       placeholder="name@example.com"
                       class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
                @error('email') <span class="mt-1.5 block text-xs text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-xs font-medium text-ink">Password</label>
                <input type="password" id="password" autocomplete="current-password" wire:model="password"
                       class="h-8 w-full rounded-md border border-line-strong bg-surface px-2.5 text-sm text-ink placeholder:text-ink-subtle cms-focus">
                @error('password') <span class="mt-1.5 block text-xs text-danger">{{ $message }}</span> @enderror
            </div>

            @if (\App\Support\Turnstile::enabled())
                {{-- wire:ignore wajib: Livewire tidak boleh merender ulang wadah ini,
                     karena widget Cloudflare menyisipkan iframe-nya sendiri di sini
                     dan akan hilang bila DOM-nya ditimpa. --}}
                <div wire:ignore>
                    <div id="turnstile-widget"
                         class="cf-turnstile"
                         data-sitekey="{{ \App\Support\Turnstile::siteKey() }}"
                         data-callback="onTurnstileSuccess"
                         data-expired-callback="onTurnstileExpired"
                         data-error-callback="onTurnstileExpired"
                         data-language="{{ app()->getLocale() }}"></div>
                </div>
            @endif

            @if (session()->has('message'))
                <p class="rounded-md border border-danger/30 bg-danger/10 px-3 py-2 text-xs text-danger">
                    {{ session('message') }}
                </p>
            @endif

            <x-cms.button type="submit" loadingTarget="login" class="w-full">Log in</x-cms.button>
        </form>
    </div>

    <p class="mt-5 text-center text-xs text-ink-muted">
        <a href="{{ url('/') }}" class="transition-colors hover:text-ink">Continue to site →</a>
    </p>
</div>
