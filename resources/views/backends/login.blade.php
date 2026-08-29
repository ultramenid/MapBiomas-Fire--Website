@extends('layouts.cms-auth')

@section('title', 'Log in')

@section('content')
    <livewire:login-component />
@endsection

@if (\App\Support\Turnstile::enabled())
    @push('scripts')
        {{-- Skrip ditaruh bersama komponennya. Skrip Livewire sudah dimuat
             di head, jadi Livewire tersedia. --}}
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        <script>
            // Id komponen dicari dari DOM: pembantu Livewire untuk menunjuk
            // instance komponen hanya tersedia di dalam view komponennya sendiri.
            function komponenLogin() {
                var wadah = document.getElementById('turnstile-widget').closest('[wire\\:id]');
                return window.Livewire.find(wadah.getAttribute('wire:id'));
            }

            // Argumen ketiga false: simpan token tanpa memicu permintaan ke server.
            function onTurnstileSuccess(token) {
                komponenLogin().set('turnstileToken', token, false);
            }

            function onTurnstileExpired() {
                komponenLogin().set('turnstileToken', '', false);
            }

            // Token sekali pakai: percobaan gagal harus memicu token baru.
            document.addEventListener('livewire:init', function () {
                Livewire.on('turnstile-reset', function () {
                    if (window.turnstile) {
                        window.turnstile.reset('#turnstile-widget');
                    }
                });
            });
        </script>
    @endpush
@endif
