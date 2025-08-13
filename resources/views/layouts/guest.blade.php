<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

@php
  // Use seu fundo CLJ aqui
  $bg = asset('storage/images/clj_logo_cover_1920x1080.webp');
@endphp

<body class="font-sans antialiased bg-black text-white">
  <div class="grid min-h-dvh grid-cols-1 lg:grid-cols-2">
    {{-- Coluna da imagem (desktop) --}}
    <section class="relative hidden lg:block min-h-dvh">
      <img src="{{ $bg }}" alt="CLJ — oração, música e amizade"
           class="absolute inset-0 w-full h-full object-cover object-[50%_36%]">
      <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/20 to-transparent"></div>
    </section>

    {{-- Coluna do formulário (slot) --}}
    <main class="flex items-center justify-center p-6 min-h-dvh">
      <div class="w-full max-w-md">
        {{ $slot }}
      </div>
    </main>
  </div>
</body>
</html>
