<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Cantos') }}
            </h2>

            <div class="flex items-center gap-2">
                <!-- Busca + Sort (desktop) -->
                <form action="" method="GET" class="hidden md:flex items-center gap-2">
                    <input
                        type="text"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Buscar por título, guia, tom…"
                        class="w-64 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <select name="sort"
                        class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-2 py-2 text-sm text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" @selected(!request('sort'))>Mais acessados</option>
                        <option value="titulo" @selected(request('sort')==='titulo')>Título (A–Z)</option>
                        <option value="recente" @selected(request('sort')==='recente')>Recentes</option>
                    </select>
                    <button class="px-3 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                        Filtrar
                    </button>
                </form>

                <!-- CTA -->
                @can('create', App\Models\Canto::class)
                <a href="{{ route('cantos.create') }}"
                   class="px-3 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 text-sm font-semibold shadow">
                    + Adicionar Canto
                </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto py-8 px-4">
        <!-- Barra sticky com filtros -->
        <div class="sticky top-16 z-20 mb-6">
            <div class="rounded-2xl bg-white/80 dark:bg-slate-900/80 backdrop-blur border border-gray-100 dark:border-slate-700 p-3">
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Busca (mobile) -->
                    <form action="" method="GET" class="md:hidden flex-1 min-w-[260px]">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Buscar por título, guia, tom…"
                            class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2 text-sm text-gray-700 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </form>

                    <!-- Chips de filtro (multi-seleção) -->
                    @php
                        $paramTipo = request()->input('tipo');
                        $sel = is_array($paramTipo)
                            ? array_values(array_unique(array_filter($paramTipo)))
                            : array_values(array_unique(array_filter(array_map('trim', explode(',', (string) $paramTipo)))));
                        $selSet = array_flip($sel);
                    @endphp

                    <a href="{{ request()->fullUrlWithQuery(['tipo'=>null]) }}"
                       class="px-4 py-2 rounded-full border font-semibold transition text-sm
                       {{ empty($sel)
                           ? 'bg-blue-600 text-white border-blue-600'
                           : 'bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-200 border-blue-200 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-700' }}">
                        Todos
                    </a>

                    @foreach ($tipos as $tipo)
                        @php
                            $isActive = isset($selSet[(string)$tipo->id]) || isset($selSet[$tipo->nome]);
                            $next = $sel;
                            if ($isActive) {
                                $next = array_values(array_filter($next, fn($v) => $v !== (string)$tipo->id && $v !== $tipo->nome));
                            } else {
                                $next[] = (string)$tipo->id; // usa id para estabilidade
                            }
                            $query = ['tipo' => $next ? implode(',', $next) : null];
                        @endphp
                        <a href="{{ request()->fullUrlWithQuery($query) }}"
                           class="px-4 py-2 rounded-full border font-semibold transition text-sm
                           {{ $isActive
                               ? 'bg-blue-600 text-white border-blue-600'
                               : 'bg-white dark:bg-gray-800 text-blue-700 dark:text-blue-200 border-blue-200 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-700' }}">
                            {{ $tipo->nome }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Lista estilo ranking -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Todos os Cantos</h1>

                @if(method_exists($cantos, 'total'))
                    <span class="text-xs px-2 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                        {{ $cantos->total() }} resultados
                    </span>
                @endif
            </div>

            <div class="divide-y divide-blue-50 dark:divide-slate-700">
                @forelse ($cantos as $index => $canto)
                    @php
                        $rank = $index + 1 + (method_exists($cantos, 'firstItem') ? ($cantos->firstItem() - 1) : 0);
                        $isTop = $rank <= 3;
                    @endphp

                    <div class="flex items-center py-5 gap-4">
                        <!-- Ranking -->
                        <div class="w-10 text-center">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full text-sm font-extrabold
                                {{ $isTop
                                    ? 'bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow'
                                    : 'bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-200 border border-blue-100 dark:border-slate-600' }}">
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
</x-app-layout>
