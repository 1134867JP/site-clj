<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CifraDocs — CLJ</title>

  <meta name="description" content="Organize os cantos do CLJ, gere PDFs com cifras e compartilhe repertórios com a equipe de música.">
  <meta property="og:title" content="CifraDocs — CLJ">
  <meta property="og:description" content="Organize cantos, gere PDFs lindos e compartilhe o repertório da missa.">
  <meta property="og:url" content="{{ url('/') }}">
  <meta property="og:image" content="{{ asset('storage/images/clj_logo_cover_1920x1080.webp') }}">
  <meta name="theme-color" content="#000000">

  <!-- Preload para LCP -->
  <link rel="preload" as="image" href="{{ asset('storage/images/clj_logo_cover_1920x1080.webp') }}">
  <link rel="preload" as="image" href="{{ asset('storage/images/clj_logo_mobile_1080x1920.webp') }}" media="(max-width:640px)">

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

@php
  $bgUltra  = asset('storage/images/clj_logo_panorama_2560x1080.webp'); // 21:9
  $bgDesk   = asset('storage/images/clj_logo_cover_1920x1080.webp');    // 16:9
  $bgMobile = asset('storage/images/clj_logo_mobile_1080x1920.webp');   // vertical
@endphp

<body class="min-h-screen flex flex-col text-white bg-black antialiased overflow-x-hidden">
  <!-- Link de acessibilidade: pular para conteúdo principal -->
  <a href="#hero" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:px-3 focus:py-2 focus:rounded-md focus:bg-white focus:text-black">Ir para conteúdo</a>

  <!-- HEADER -->


  <!-- BACKGROUND (imagem + efeitos) -->
  <div class="fixed inset-0 -z-30" aria-hidden="true">
    <picture>
      <source media="(min-aspect-ratio:21/9)" srcset="{{ $bgUltra }}">
      <source media="(min-width:768px)" srcset="{{ $bgDesk }}">
      <img src="{{ $bgMobile }}" alt="CLJ — oração, música e amizade"
           class="w-full h-full object-cover object-[50%_36%] select-none pointer-events-none"
           fetchpriority="high" decoding="async">
    </picture>
  </div>
  <!-- Vignette + aurora + estrelas -->
  <div class="fixed inset-0 -z-20 pointer-events-none vignette"></div>
  <div class="fixed inset-0 -z-20 pointer-events-none mix-blend-screen opacity-80 aurora-clj"></div>
  <div class="fixed inset-0 -z-20 pointer-events-none stars"></div>

  <!-- HERO -->
  <section id="hero" class="relative z-10 flex-1 pt-28 pb-14 md:pb-24">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-10">
      <!-- Texto + CTA -->
      <div class="lg:col-span-6 self-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-medium bg-neutral-900/80 border border-white/20">
          CLJ • Curso de Liderança Juvenil
        </span>
        <h1 class="mt-4 text-4xl md:text-5xl font-extrabold leading-tight tracking-tight drop-shadow-[0_2px_12px_rgba(0,0,0,.25)]">
          Seus cantos, organizados e prontos pra missa.
        </h1>
        <p class="mt-4 text-white/90 max-w-xl">
          Busque por título ou categoria, monte repertórios e gere PDFs com cifras em segundos.
        </p>
        <div class="mt-8 flex flex-wrap gap-4">
          <a href="{{ route('login') }}"
             class="px-6 py-3 rounded-xl font-semibold shadow-lg bg-gradient-to-br from-indigo-500 to-violet-600 hover:brightness-110 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-400 transition">
            Entrar agora
          </a>
          @if (Route::has('register'))
          <a href="{{ route('register') }}"
             class="px-6 py-3 rounded-xl font-semibold shadow-lg bg-neutral-900/80 hover:bg-neutral-700/80 border border-white/25 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-white/60 transition">
            Criar conta
          </a>
          @endif
        </div>
      </div>
    </div>
  </section>



  <!-- FOOTER -->
  <footer class="relative z-10 pb-10 pt-6">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-3 text-sm text-white/70">
      <p>© {{ date('Y') }} CifraDocs — CLJ</p>
    </div>
  </footer>

  <!-- estilos visuais -->
  <style>
    .vignette{
      background:
        radial-gradient(1200px 600px at 50% 40%, rgba(0,0,0,.10), transparent 65%),
        radial-gradient(1400px 800px at 50% 110%, rgba(0,0,0,.35), transparent 60%),
        radial-gradient(1600px 900px at -10% -10%, rgba(0,0,0,.25), transparent 55%),
        radial-gradient(1600px 900px at 110% -10%, rgba(0,0,0,.25), transparent 55%);
    }
    .aurora-clj{
      background:
        radial-gradient(50% 40% at 50% 20%, rgba(255,196,76,.35), transparent 60%),
        radial-gradient(40% 35% at 12% 65%, rgba(251,146,60,.28), transparent 60%),
        radial-gradient(45% 35% at 88% 70%, rgba(253,186,116,.24), transparent 60%);
      filter: blur(36px) saturate(112%);
      animation: aurora-move 24s ease-in-out infinite alternate;
    }
    @keyframes aurora-move{
      0%   { transform: translate3d(0,0,0) scale(1); opacity:.85; }
      50%  { transform: translate3d(-1.2%, -1%, 0) scale(1.02); opacity:.95; }
      100% { transform: translate3d(1.2%, 1%, 0) scale(1.01); opacity:.90; }
    }
    .stars{
      background-image:
        radial-gradient(1px 1px at 10% 20%, rgba(255,255,255,.8) 50%, transparent 51%),
        radial-gradient(1px 1px at 30% 78%, rgba(255,255,255,.75) 50%, transparent 51%),
        radial-gradient(1px 1px at 70% 32%, rgba(255,255,255,.8) 50%, transparent 51%),
        radial-gradient(1px 1px at 85% 66%, rgba(255,255,255,.7) 50%, transparent 51%),
        radial-gradient(1px 1px at 45% 52%, rgba(255,255,255,.7) 50%, transparent 51%);
      background-repeat: no-repeat;
      animation: twinkle 6s ease-in-out infinite alternate;
      opacity:.55;
    }
    @keyframes twinkle{
      from { opacity:.4; transform: translateY(0px); }
      to   { opacity:.8; transform: translateY(-.5px); }
    }
  </style>
</body>
</html>
