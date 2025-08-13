<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recuperar senha — CifraDocs</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
@php $bg = asset('storage/images/clj_logo_cover_1920x1080.webp'); @endphp
<body class="text-white antialiased">
  <div class="fixed inset-0 -z-20" aria-hidden="true">
    <img src="{{ $bg }}" class="w-full h-full object-cover"
         style="object-position:50% 36%;
                filter:blur(14px) saturate(110%) brightness(0.25);
                transform:scale(1.06);">
  </div>
  <div class="fixed inset-0 -z-10" aria-hidden="true"
       style="background:
         radial-gradient(1200px 600px at 50% 40%, rgba(0,0,0,.18), transparent 65%),
         radial-gradient(1400px 800px at 50% 110%, rgba(0,0,0,.30), transparent 60%);"></div>

  <div class="grid min-h-[100svh] grid-cols-1 lg:grid-cols-2">
    <section class="relative hidden lg:block">
      <img src="{{ $bg }}" class="absolute inset-0 w-full h-full object-cover" style="object-position:50% 36%">
      <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/15 to-transparent"></div>
      <div class="absolute bottom-8 left-8 right-10">
        <h2 class="text-2xl font-semibold drop-shadow">Esqueceu a senha?</h2>
        <p class="text-white/85">Enviaremos um link de redefinição para seu email.</p>
      </div>
    </section>

    <main class="flex items-center justify-center p-6">
      <div class="w-full max-w-md">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-6 text-white/85 hover:text-white">
          <x-application-logo class="h-8 w-auto"/><span class="sr-only">CifraDocs</span>
        </a>

        <div class="rounded-2xl bg-neutral-900/75 backdrop-blur-xl border border-white/10 shadow-2xl p-8">
          <h1 class="text-2xl font-bold mb-1">Recuperar senha</h1>
          <p class="text-white/80 mb-6">Informe seu email para receber o link de recuperação.</p>

          @if (session('status'))
            <div class="mb-4 text-sm text-emerald-300">{{ session('status') }}</div>
          @endif

          <form method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf
            <div>
              <x-input-label for="email" :value="__('Email')" class="text-white"/>
              <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username"/>
              <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <x-primary-button class="w-full justify-center mt-6">{{ __('Enviar link') }}</x-primary-button>
          </form>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
