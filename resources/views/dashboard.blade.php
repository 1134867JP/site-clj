<x-app-layout>
  <div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Hero / Header (glass) -->
    <div class="rounded-2xl border border-white/20 bg-white/80 supports-[backdrop-filter]:bg-white/60 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur shadow-xl px-6 py-5 flex items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
          Bem-vindo ao CifraDocs
        </h1>
        <p class="mt-1 text-slate-700/90 dark:text-slate-300">
          {{ __('Acesse todos os cantos e gere PDF para suas missas!') }}
        </p>
      </div>

      <!-- Ação principal -->
      <a href="{{ route('cantos.index') }}"
         class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow-lg hover:shadow-xl hover:bg-indigo-700 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-2v13"/>
        </svg>
        Acessar Cantos
      </a>
    </div>

    <!-- Busca -->
    <div class="mt-6">
      <form action="{{ route('cantos.index') }}" method="GET" class="relative">
        <input type="text" name="q" placeholder="Buscar cantos por título, parte da missa ou tom…"
               class="w-full rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-5 py-4 pr-12 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition placeholder:text-slate-400 dark:text-slate-100" />
        <button class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 transition" type="submit">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
          </svg>
        </button>
      </form>
    </div>

    <!-- Cards de Métricas -->
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="group rounded-2xl bg-white/80 supports-[backdrop-filter]:bg-white/60 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur border border-white/20 p-5 shadow-xl hover:shadow-2xl hover:bg-white/85 dark:hover:bg-neutral-900/75 transition">
        <div class="flex items-center justify-between">
          <p class="text-sm text-slate-600 dark:text-slate-400">Total de Cantos</p>
          <span class="rounded-lg bg-indigo-100/70 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 px-2 py-1 text-xs font-semibold">Geral</span>
        </div>
        <div class="mt-3 flex items-end justify-between">
          <h3 class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $qtCantos ?? '—' }}</h3>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500/80" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 3l2.12 6.49h6.82l-5.52 4.01 2.11 6.5L12 16.99l-5.53 3.99 2.12-6.5L3.06 9.49h6.82L12 3z"/>
          </svg>
        </div>
      </div>

      <div class="group rounded-2xl bg-white/80 supports-[backdrop-filter]:bg-white/60 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur border border-white/20 p-5 shadow-xl hover:shadow-2xl hover:bg-white/85 dark:hover:bg-neutral-900/75 transition">
        <div class="flex items-center justify-between">
          <p class="text-sm text-slate-600 dark:text-slate-400">Categorias</p>
          <span class="rounded-lg bg-emerald-100/70 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 px-2 py-1 text-xs font-semibold">Liturgia</span>
        </div>
        <div class="mt-3 flex items-end justify-between">
          <h3 class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $qtCategorias ?? '—' }}</h3>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-500/80" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 5h18v2H3zM3 11h18v2H3zM3 17h18v2H3z"/>
          </svg>
        </div>
      </div>
    </div>

    <!-- Ações rápidas -->
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <a href="{{ route('cantos.index') }}"
         class="rounded-2xl bg-white/80 supports-[backdrop-filter]:bg-white/60 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur border border-white/20 p-4 shadow-md hover:shadow-xl hover:-translate-y-0.5 transition flex items-center gap-3">
        <div class="rounded-xl p-3 bg-indigo-100/80 dark:bg-indigo-900/40">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-700 dark:text-indigo-300" viewBox="0 0 24 24" fill="currentColor">
            <path d="M4 5h16v14H4zM7 8h10v2H7zM7 12h10v2H7z"/>
          </svg>
        </div>
        <div>
          <p class="font-semibold text-slate-900 dark:text-slate-100">Ver todos os Cantos</p>
          <p class="text-sm text-slate-600 dark:text-slate-400">Listagem completa</p>
        </div>
      </a>

      @can('create', App\Models\Canto::class)
      <a href="{{ route('cantos.create') }}"
         class="rounded-2xl bg-white/80 supports-[backdrop-filter]:bg-white/60 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur border border-white/20 p-4 shadow-md hover:shadow-xl hover:-translate-y-0.5 transition flex items-center gap-3">
        <div class="rounded-xl p-3 bg-emerald-100/80 dark:bg-emerald-900/40">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-700 dark:text-emerald-300" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 7v10M7 12h10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <div>
          <p class="font-semibold text-slate-900 dark:text-slate-100">Novo Canto</p>
          <p class="text-sm text-slate-600 dark:text-slate-400">Cadastrar música</p>
        </div>
      </a>
      @endcan

      @can('create', App\Models\CantoTipo::class)
      <a href="{{ route('settings.general') }}"
         class="rounded-2xl bg-white/80 supports-[backdrop-filter]:bg-white/60 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur border border-white/20 p-4 shadow-md hover:shadow-xl hover:-translate-y-0.5 transition flex items-center gap-3">
        <div class="rounded-xl p-3 bg-amber-100/80 dark:bg-amber-900/40">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-700 dark:text-amber-300" viewBox="0 0 24 24" fill="currentColor">
            <path d="M4 6h16v4H4zM4 14h16v4H4z"/>
          </svg>
        </div>
        <div>
          <p class="font-semibold text-slate-900 dark:text-slate-100">Configurações</p>
          <p class="text-sm text-slate-600 dark:text-slate-400">Tipos de músicas</p>
        </div>
      </a>
      @endcan

      <a href="{{ route('cantos.selecionar') }}"
         class="rounded-2xl bg-white/80 supports-[backdrop-filter]:bg-white/60 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur border border-white/20 p-4 shadow-md hover:shadow-xl hover:-translate-y-0.5 transition flex items-center gap-3">
        <div class="rounded-xl p-3 bg-fuchsia-100/80 dark:bg-fuchsia-900/40">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-fuchsia-700 dark:text-fuchsia-300" viewBox="0 0 24 24" fill="currentColor">
            <path d="M6 7h12v2H6zM6 11h12v2H6zM6 15h8v2H6z"/>
          </svg>
        </div>
        <div>
          <p class="font-semibold text-slate-900 dark:text-slate-100">Seleção p/ Missa</p>
          <p class="text-sm text-slate-600 dark:text-slate-400">Montar repertório</p>
        </div>
      </a>
    </div>

    <!-- Últimos adicionados -->
    <div class="mt-10">
      <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-3">Últimos adicionados</h2>
      <div class="rounded-2xl bg-white/75 supports-[backdrop-filter]:bg-white/55 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur border border-white/20 p-4 shadow-md">
        @if(!empty($recentCantos) && count($recentCantos))
          <ul class="divide-y divide-white/10 dark:divide-white/10">
            @foreach($recentCantos as $rc)
              <li class="py-3 flex items-center justify-between gap-3">
                <div>
                  <a href="{{ route('cantos.show', $rc) }}" class="font-semibold text-indigo-700 dark:text-indigo-300 hover:underline">{{ $rc->titulo }}</a>
                  <div class="text-xs text-slate-600 dark:text-slate-400">
                    {{ $rc->tipos->pluck('nome')->join(', ') ?: '—' }} • {{ $rc->created_at?->format('d/m/Y') }}
                  </div>
                </div>
                <div class="flex items-center gap-2">
                  <a href="{{ route('cantos.show', $rc) }}" class="px-2 py-1 text-xs rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Abrir</a>
                  @can('update',$rc)
                  <a href="{{ route('cantos.edit', $rc) }}" class="px-2 py-1 text-xs rounded-lg bg-amber-500 text-white hover:bg-amber-600">Editar</a>
                  @endcan
                </div>
              </li>
            @endforeach
          </ul>
        @else
          <p class="text-sm text-slate-600 dark:text-slate-400">Sem itens recentes.</p>
        @endif
      </div>
    </div>

    <!-- Dicas / Atalhos -->
    <div class="mt-8 rounded-2xl bg-white/75 supports-[backdrop-filter]:bg-white/55 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur border border-white/20 p-6 shadow-md">
      <div class="flex items-start gap-4">
        <div class="rounded-xl p-3 bg-sky-100/80 dark:bg-sky-900/40">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-sky-700 dark:text-sky-300" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 15h-2v-2h2Zm0-4h-2V7h2Z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Dica rápida</h3>
          <p class="mt-1 text-slate-700/90 dark:text-slate-300">
            Use a busca para encontrar cantos e depois filtre por guia litúrgica na listagem. Para impressão, gere o PDF direto da seleção.
          </p>
        </div>
      </div>
    </div>

    <!-- Rodapé slim -->
    <div class="mt-8 text-center text-xs text-slate-600 dark:text-slate-400">
      Feito com ❤️ — {{ config('app.name') }}
    </div>

    @include('partials.feedback-form')
  </div>
</x-app-layout>
