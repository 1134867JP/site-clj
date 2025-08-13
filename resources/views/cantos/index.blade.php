<x-app-layout>
    {{-- Removido header para seguir o layout do "Gerar PDF" --}}

    @php
        $paramTipo = request()->input('tipo');
        $selectedTipos = is_array($paramTipo)
            ? array_values(array_unique(array_filter($paramTipo)))
            : array_values(array_unique(array_filter(array_map('trim', explode(',', (string) $paramTipo)))));
    @endphp

    <div class="max-w-6xl mx-auto py-8 px-4">
        <!-- Barra com filtros (não-sticky) -->
        <div class="mb-6">
            <div
                class="relative z-[200] rounded-2xl bg-white/70 dark:bg-neutral-900/50 backdrop-blur border border-white/10 p-3 supports-[backdrop-filter]:bg-white/50 dark:supports-[backdrop-filter]:bg-neutral-900/50"
                x-data="{ 
                    showTipos:false,
                    selected: @js($selectedTipos)
                }"
            >
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Busca (igual Selecionar) -->
                    <form action="" method="GET" class="flex-1 min-w-[260px] flex items-center gap-2" x-ref="filterForm">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Buscar por título…"
                            class="w-full h-10 rounded-xl border border-white/10 bg-white/40 dark:bg-white/10 px-3 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-500 dark:placeholder:text-white/70 focus:outline-none focus:ring-2 focus:ring-blue-500 backdrop-blur"
                        />
                        <button class="px-3 h-10 inline-flex items-center justify-center rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700" type="submit">
                          Buscar
                        </button>
                        <a href="{{ route('cantos.index') }}"
                           class="px-3 h-10 inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/30 dark:bg-white/10 text-sm text-gray-900 dark:text-gray-100 hover:bg-white/40 backdrop-blur">
                          Limpar
                        </a>

                        <!-- Mantém outros filtros ao enviar -->
                        <input type="hidden" name="tipo" x-ref="tipoInput" value="{{ request()->has('tipo') ? (is_array(request('tipo')) ? implode(',', request('tipo')) : request('tipo')) : '' }}">
                        @if(request()->has('sort'))
                          <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif
                    </form>

                    <!-- Tipos: popover compacto com múltipla seleção -->
                    <div class="relative">
                        <button x-ref="tiposBtn" type="button"
                                @click="showTipos = !showTipos"
                                aria-haspopup="dialog" :aria-expanded="showTipos"
                                class="px-4 h-10 inline-flex items-center gap-2 rounded-xl border font-semibold text-sm transition
                                       bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-200 border-blue-200 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-700">
                            Tipos
                            <span x-show="selected.length" x-cloak
                                  class="ml-1 text-[10px] px-2 py-0.5 rounded-full bg-blue-600 text-white">
                                <span x-text="selected.length"></span>
                            </span>
                        </button>

                        <div x-cloak x-show="showTipos" x-transition.opacity.scale.origin.top.right
                             @keydown.escape.window="showTipos=false"
                             class="absolute right-0 mt-2 w-72 rounded-2xl bg-white/40 dark:bg-neutral-900/90 supports-[backdrop-filter]:bg-white/80 dark:supports-[backdrop-filter]:bg-neutral-900/80 backdrop-blur-sm border border-white/20 dark:border-white/10 shadow-2xl p-3 z-[500]">
                            <div class="max-h-64 overflow-auto pr-1">
                                @foreach ($tipos as $tipo)
                                    <label class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-blue-50/70 dark:hover:bg-slate-700/50 cursor-pointer">
                                        <input type="checkbox" class="h-4 w-4 rounded border-gray-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500"
                                               :value="'{{ (string)$tipo->id }}'" x-model="selected">
                                        <span class="text-sm text-gray-800 dark:text-gray-200">{{ $tipo->nome }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-between gap-2 pt-3">
                                <button type="button" class="text-sm px-3 py-1.5 rounded-lg border border-transparent text-gray-700 dark:text-gray-200 hover:underline"
                                        @click="selected=[]; $refs.tipoInput.value=''; $refs.filterForm.submit()">
                                    Limpar
                                </button>
                                <div class="flex gap-2">
                                    <button type="button" class="text-sm px-3 py-1.5 rounded-lg border border-white/10 bg-white/40 dark:bg-white/10 text-gray-800 dark:text-gray-200 hover:bg-white/60"
                                            @click="showTipos=false">
                                        Fechar
                                    </button>
                                    <button type="button" class="text-sm px-3 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700"
                                            @click="$refs.tipoInput.value = selected.join(','); $refs.filterForm.submit()">
                                        Aplicar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista estilo ranking -->
        <div class="relative z-10 overflow-hidden bg-white/70 dark:bg-neutral-900/70 supports-[backdrop-filter]:bg-white/50 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur rounded-2xl border border-white/10 shadow-2xl p-8">
            <!-- Removido título grande -->
            @if(method_exists($cantos, 'total'))
                <div class="mb-4 text-right">
                    <span class="text-xs px-2 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                        {{ $cantos->total() }} resultados
                    </span>
                </div>
            @endif

            <div class="divide-y divide-white/10">
                @forelse ($cantos as $index => $canto)
                    @php
                        $rank = $index + 1 + (method_exists($cantos, 'firstItem') ? ($cantos->firstItem() - 1) : 0);
                        $isTop = $rank <= 3;
                    @endphp

                    <div class="flex items-center py-5 gap-4 rounded-xl hover:bg-white/45 dark:hover:bg-white/10 transition-colors px-4">
                        <!-- Ranking -->
                        <div class="w-10 text-center">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full text-sm font-extrabold
                                {{ $isTop
                                    ? 'bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow'
                                    : 'bg-white dark:bg-slate-700 text-blue-700 dark:text-blue-200 border border-white/20 dark:border-slate-600' }}">
                                {{ $rank }}
                            </span>
                        </div>

                        <!-- Conteúdo -->
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('cantos.show', $canto) }}"
                                   class="text-lg font-semibold text-blue-800 dark:text-blue-300 hover:underline">
                                    {{ $canto->titulo }}
                                </a>

                                @if(!empty($canto->tom))
                                    <span class="text-[10px] uppercase tracking-wide px-2 py-0.5 rounded-full border
                                        bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300
                                        border-gray-200 dark:border-slate-600">
                                        Tom {{ $canto->tom }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $canto->tipos->pluck('nome')->join(', ') }}
                                @if(!empty($canto->guia))
                                    • {{ $canto->guia }}
                                @endif
                            </div>
                        </div>

                        <!-- Ações -->
                        <div class="flex gap-2">
                            <a href="{{ route('cantos.show', $canto) }}"
                               class="px-3 py-1 rounded-lg text-xs font-medium
                                      bg-blue-600 text-white hover:bg-blue-700
                                      dark:bg-blue-500 dark:hover:bg-blue-600">
                                Ver
                            </a>
                            @can('update', $canto)
                            <a href="{{ route('cantos.edit', $canto) }}"
                               class="px-3 py-1 rounded-lg text-xs font-medium
                                      bg-amber-500 text-white hover:bg-amber-600
                                      dark:bg-amber-400 dark:hover:bg-amber-500">
                                Editar
                            </a>
                            @endcan
                            @can('delete', $canto)
                            <form method="POST" action="{{ route('cantos.destroy', $canto) }}" onsubmit="return confirm('Remover?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1 rounded-lg text-xs font-medium bg-red-600 text-white hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600">Excluir</button>
                            </form>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center">
                        <div class="mx-auto mb-3 h-12 w-12 rounded-full bg-blue-50 dark:bg-slate-700 flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-500 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                            </svg>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 font-medium">Nenhum canto encontrado.</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Tente ajustar os filtros
                            @can('create', App\Models\Canto::class)
                                ou
                                <a class="text-blue-600 dark:text-blue-300 hover:underline" href="{{ route('cantos.create') }}">adicione um novo canto</a>
                            @endcan
                            .
                        </p>
                    </div>
                @endforelse
            </div>

            @if(method_exists($cantos, 'links'))
                <div class="mt-6">
                    {{ $cantos->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Botão flutuante: voltar ao topo -->
    <div x-data="{ showTop:false }" x-init="window.addEventListener('scroll', () => { showTop = window.scrollY > 300 })"
         class="fixed bottom-6 right-6 z-40">
        <button x-show="showTop" x-transition
                @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                type="button"
                class="h-12 w-12 rounded-full bg-white/70 dark:bg-neutral-900/70 supports-[backdrop-filter]:bg-white/50 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur border border-white/20 shadow-xl flex items-center justify-center text-blue-700 dark:text-blue-200 hover:bg-white/80">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>
</x-app-layout>
