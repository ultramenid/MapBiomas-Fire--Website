<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Log in') · MapBiomas Fire</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function () {
            var stored = localStorage.getItem('cms-theme');
            var dark = stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (dark) document.documentElement.classList.add('dark');
        })();
        window.toggleTheme = function () {
            var isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('cms-theme', isDark ? 'dark' : 'light');
        };
    </script>
    @livewireStyles
    @livewireScripts
</head>
<body class="cms-page min-h-screen bg-canvas font-sans text-ink antialiased">
<div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
    @yield('content')
    <button type="button" onclick="toggleTheme()"
            class="mt-8 inline-flex h-8 w-8 items-center justify-center rounded-md text-ink-muted transition-colors hover:bg-hover hover:text-ink"
            title="Toggle theme">
        <svg class="h-4 w-4 dark:hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
        </svg>
        <svg class="hidden h-4 w-4 dark:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="4" />
            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" />
        </svg>
    </button>
</div>
<x-toaster-hub />
@stack('scripts')
</body>
</html>
