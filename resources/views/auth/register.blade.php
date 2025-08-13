<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrar — CifraDocs</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
@php $bg = asset('storage/images/clj_logo_cover_1920x1080.webp'); @endphp
<body class="text-white antialiased">
  <!-- Fundo esmaecido global -->
  <div aria-hidden="true" class="fixed inset-0 -z-20">
    <img src="{{ $bg }}" class="w-full h-full object-cover"
         style="object-position:50% 36%;
                filter:blur(14px) saturate(110%) brightness(0.25);
                transform:scale(1.06);">
  </div>
  <div aria-hidden="true" class="fixed inset-0 -z-10"
       style="background:
         radial-gradient(1200px 600px at 50% 40%, rgba(0,0,0,.18), transparent 65%),
         radial-gradient(1400px 800px at 50% 110%, rgba(0,0,0,.30), transparent 60%);"></div>

  <div class="grid min-h-[100svh] grid-cols-1 lg:grid-cols-2">
    <!-- Imagem nítida no desktop -->
    <section class="relative hidden lg:block">
      <img src="{{ $bg }}" class="absolute inset-0 w-full h-full object-cover" style="object-position:50% 36%">
      <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/15 to-transparent"></div>
      <div class="absolute bottom-8 left-8 right-10">
        <h2 class="text-2xl font-semibold drop-shadow">Crie sua conta</h2>
        <p class="text-white/85">Organize cantos, gere PDFs e compartilhe com a equipe.</p>
      </div>
    </section>

    <!-- Formulário -->
    <main class="flex items-center justify-center p-6">
      <div class="w-full max-w-md">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-6 text-white/85 hover:text-white">
          <x-application-logo class="h-8 w-auto"/><span class="sr-only">CifraDocs</span>
        </a>

        <div class="rounded-2xl bg-neutral-900/75 backdrop-blur-xl border border-white/10 shadow-2xl p-8">
          <h1 class="text-2xl font-bold mb-1">Criar conta</h1>
          <p class="text-white/80 mb-6">Leva menos de 1 minuto.</p>

          <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf
            <div>
              <x-input-label for="name" :value="__('Nome')" class="text-white"/>
              <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus autocomplete="name"/>
              <x-input-error :messages="$errors->get('name')" class="mt-2"/>
            </div>

            <div class="mt-4">
              <x-input-label for="email" :value="__('Email')" class="text-white"/>
              <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autocomplete="username"
                            autocapitalize="off" spellcheck="false"/>
              <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <div class="mt-4">
              <x-input-label for="password" :value="__('Senha')" class="text-white"/>
              <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-14" type="password" name="password" required autocomplete="new-password"/>
                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-white/70 hover:text-white"
                        onclick="this.previousElementSibling.type=this.previousElementSibling.type==='password'?'text':'password'">👁️</button>
              </div>
              <x-input-error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <div class="mt-4">
              <x-input-label for="password_confirmation" :value="__('Confirmar senha')" class="text-white"/>
              <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password" name="password_confirmation" required autocomplete="new-password"/>
              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
            </div>

            <label class="inline-flex items-center mt-4">
              <input type="checkbox" name="terms" required
                     class="rounded border-white/30 bg-transparent text-indigo-400 focus:ring-indigo-400">
              <span class="ms-2 text-sm text-white/80">
                Aceito os <a href="#" class="underline underline-offset-4 hover:opacity-90">termos</a> e a
                <a href="#" class="underline underline-offset-4 hover:opacity-90">política</a>.
              </span>
            </label>

            <div class="mt-6 flex items-center justify-between">
              <a class="text-sm text-white/80 hover:text-white underline underline-offset-4" href="{{ route('login') }}">
                Já tem conta? Entrar
              </a>
              <x-primary-button class="ms-3">{{ __('Registrar') }}</x-primary-button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
