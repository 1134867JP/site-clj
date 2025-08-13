<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Entrar — CifraDocs</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet"/>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
@php $bg = asset('storage/images/clj_logo_cover_1920x1080.webp'); @endphp
<body class="text-white antialiased">
  <!-- Fundo esmaecido (vale para a tela toda) -->
  <div aria-hidden="true" class="fixed inset-0 -z-20">
    <img src="{{ $bg }}" class="w-full h-full object-cover"
         style="object-position:50% 36%;
       filter:blur(14px) saturate(110%) brightness(0.25);
       transform:scale(1.06);">
  </div>
  <div aria-hidden="true" class="fixed inset-0 -z-10"
       style="background:radial-gradient(1200px 600px at 50% 40%, rgba(0,0,0,.18), transparent 65%),
                          radial-gradient(1400px 800px at 50% 110%, rgba(0,0,0,.30), transparent 60%)"></div>

  <div class="grid min-h-[100svh] grid-cols-1 lg:grid-cols-2">
    <!-- Imagem nítida no desktop -->
    <section class="relative hidden lg:block">
      <img src="{{ $bg }}" class="absolute inset-0 w-full h-full object-cover" style="object-position:50% 36%">
      <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/15 to-transparent"></div>
      <div class="absolute bottom-8 left-8 right-10">
        <h2 class="text-2xl font-semibold drop-shadow">Bem-vindo de volta 👋</h2>
        <p class="text-white/85">Entre para acessar seus cantos do CLJ.</p>
      </div>
    </section>

    <!-- Formulário -->
    <main class="flex items-center justify-center p-6">
      <div class="w-full max-w-md">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-6 text-white/85 hover:text-white">
          <x-application-logo class="h-8 w-auto"/><span class="sr-only">CifraDocs</span>
        </a>

        <div class="rounded-2xl bg-neutral-900/75 backdrop-blur-xl border border-white/10 shadow-2xl p-8">
          <h1 class="text-2xl font-bold mb-1">Entrar</h1>
          <p class="text-white/80 mb-6">Acesse sua conta para continuar.</p>

          <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf
            <div>
              <x-input-label for="email" :value="__('Email')" class="text-white"/>
              <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username"
                            autocapitalize="off" spellcheck="false"/>
              <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <div class="mt-4">
              <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Senha')" class="text-white"/>
                @if (Route::has('password.request'))
                  <a class="text-xs text-white/80 hover:text-white" href="{{ route('password.request') }}">Esqueceu a senha?</a>
                @endif
              </div>
              <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-14" type="password" name="password" required autocomplete="current-password"/>
                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-white/70 hover:text-white"
                        onclick="this.previousElementSibling.type=this.previousElementSibling.type==='password'?'text':'password'">👁️</button>
              </div>
              <x-input-error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <label class="inline-flex items-center mt-4">
              <input id="remember_me" type="checkbox" class="rounded border-white/30 bg-transparent text-indigo-400 focus:ring-indigo-400" name="remember">
              <span class="ms-2 text-sm text-white/80">Lembrar de mim</span>
            </label>

            <x-primary-button id="loginBtn" class="w-full justify-center mt-6">{{ __('Entrar') }}</x-primary-button>
          </form>

          @if (Route::has('register'))
            <p class="mt-6 text-sm text-white/80">Novo por aqui?
              <a href="{{ route('register') }}" class="text-white underline underline-offset-4 hover:opacity-90">Crie sua conta</a>.
            </p>
          @endif
        </div>
      </div>
    </main>
  </div>
  <script>
    const f=document.querySelector('form'), b=document.getElementById('loginBtn');
    if(f&&b){ f.addEventListener('submit',()=>{b.disabled=true;b.classList.add('opacity-75','cursor-not-allowed');});}
  </script>
</body>
</html>
