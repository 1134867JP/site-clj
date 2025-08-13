<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="color-scheme" content="light dark">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Prevenção de “flash” de tema / inicialização do dark mode -->
        <script>
            (function () {
                try {
                    const stored = localStorage.getItem('theme');
                    const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const shouldDark = stored ? stored === 'dark' : systemPrefersDark;
                    document.documentElement.classList.toggle('dark', shouldDark);
                } catch (e) { /* no-op */ }
            })();
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php $bg = asset('storage/images/clj_logo_cover_1920x1080.webp'); @endphp
    <body class="font-sans antialiased bg-gray-100 text-slate-900 dark:bg-slate-900 dark:text-slate-100 transition-colors">
        <!-- Global blurred background + gradients (behind app) -->
        <!-- Light mode background -->
        <div aria-hidden="true" class="fixed inset-0 -z-20 block dark:hidden">
            <img src="{{ $bg }}" class="w-full h-full object-cover" style="object-position:50% 36%; filter:blur(14px) saturate(110%) brightness(0.65); transform:scale(1.06);">
        </div>
        <div aria-hidden="true" class="fixed inset-0 -z-10 block dark:hidden"
             style="background:radial-gradient(1200px 600px at 50% 40%, rgba(0,0,0,.08), transparent 65%),
                                radial-gradient(1400px 800px at 50% 110%, rgba(0,0,0,.14), transparent 60%)"></div>

        <!-- Dark mode background -->
        <div aria-hidden="true" class="fixed inset-0 -z-20 hidden dark:block">
            <img src="{{ $bg }}" class="w-full h-full object-cover" style="object-position:50% 36%; filter:blur(14px) saturate(110%) brightness(0.25); transform:scale(1.06);">
        </div>
        <div aria-hidden="true" class="fixed inset-0 -z-10 hidden dark:block"
             style="background:radial-gradient(1200px 600px at 50% 40%, rgba(0,0,0,.18), transparent 65%),
                                radial-gradient(1400px 800px at 50% 110%, rgba(0,0,0,.30), transparent 60%)"></div>

        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="sticky top-0 z-10 bg-white/70 supports-[backdrop-filter]:bg-white/50 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur border-b border-white/10">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="min-h-[calc(100vh-4rem)]">
                {{ $slot }}
            </main>

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
                     class="fixed bottom-4 left-1/2 -translate-x-1/2 z-40 px-4 py-2 rounded-xl bg-green-600 text-white shadow">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Feedback form moved to dashboard only -->
        </div>
    </body>
</html>
