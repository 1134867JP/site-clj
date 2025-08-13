<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verifique seu email — CifraDocs</title>
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
        <h2 class="text-2xl font-semibold drop-shadow">Verifique seu email</h2>
        <p class="text-white/85">Enviamos um link de verificação para você.</p>
      </div>
    </section>

    <main class="flex items-center justify-center p-6">
      <div class="w-full max-w-md">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-6 text-white/85 hover:text-white">
          <x-application-logo class="h-8 w-auto"/><span class="sr-only">CifraDocs</span>
        </a>

        <div class="rounded-2xl bg-neutral-900/75 backdrop-blur-xl border border-white/10 shadow-2xl p-8">
          <h1 class="text-2xl font-bold mb-3">Confirme seu email</h1>

          <p class="text-white/85">
            Antes de continuar, verifique o link que enviamos para
            <strong>{{ auth()->user()->email ?? 'seu email' }}</strong>.
            Caso não tenha recebido, podemos enviar outro.
          </p>

          @if (session('status') == 'verification-link-sent')
            <div class="mt-4 text-sm text-emerald-300">
              Um novo link de verificação foi enviado para seu email.
            </div>
          @endif

          <div class="mt-6 flex items-center gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
              @csrf
              <x-primary-button>{{ __('Reenviar email') }}</x-primary-button>
            </form>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="text-sm text-white/80 underline underline-offset-4 hover:opacity-90">
                {{ __('Sair') }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
