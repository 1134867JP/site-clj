<x-app-layout>
    <div 
        x-data="cantosSel()"
        x-init="init()"
        class="max-w-6xl mx-auto py-8 px-4"
    >
        <form method="GET" action="{{ route('cantos.selecionar') }}" id="formCantos">
            <!-- Filtros / Ações -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                <div class="flex items-center gap-3">
                    <label for="tipo" class="font-semibold text-gray-800 dark:text-gray-100">Filtrar por tipo:</label>
                    <select name="tipo" id="tipo" onchange="this.form.submit()"
                            class="w-full md:w-auto border border-blue-300 dark:border-slate-600 rounded-xl px-3 py-2 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo }}" {{ request('tipo') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 flex items-center justify-between gap-3">
                    <div class="flex-1">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por título…"
                               class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-gray-700 dark:text-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Botão Filtrar -->
                    <button type="submit"
                            class="px-4 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-sm font-semibold text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700">
                        Filtrar
                    </button>
                </div>
            </div>

            <!-- Barra de seleção -->
            <div class="mb-3 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-gray-100 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 backdrop-blur p-3">
                <div class="flex items-center gap-3">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" @change="toggleAll($event)" :checked="allOnPageChecked"
                               class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-gray-700 dark:text-gray-200">Selecionar todos na página</span>
                    </label>

                    <button type="button" @click="clearAll()"
                            class="text-sm px-3 py-1 rounded-lg bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-slate-700">
                        Limpar seleção
                    </button>
                </div>

                <div class="text-sm">
                    <span class="px-2 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                        <strong x-text="selected.size"></strong> selecionado(s)
                    </span>
                </div>
            </div>

            <!-- Tabela -->
            <div class="overflow-x-auto rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow">
                <table class="min-w-full">
                    <thead class="sticky z-20 top-[var(--app-header,0px)] bg-gray-50/95 dark:bg-slate-900/95 backdrop-blur border-b border-gray-200 dark:border-slate-700">
                        <tr class="text-left text-sm text-gray-600 dark:text-gray-300">
                            <th class="px-4 py-3 w-14">Selecionar</th>
                            <th class="px-4 py-3">Título</th>
                            <th class="px-4 py-3">Tipo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse ($cantos as $canto)
                            <tr class="hover:bg-blue-50 dark:hover:bg-slate-700/50 transition">
                                <td class="px-4 py-3">
                                    <input type="checkbox"
                                           :id="'canto-' + {{ $canto->id }}"
                                           name="ids[]"
                                           value="{{ $canto->id }}"
                                           @change="toggle({{ $canto->id }})"
                                           :checked="selected.has({{ $canto->id }})"
                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-4 py-3">
                                    <label :for="'canto-' + {{ $canto->id }}"
                                           class="font-semibold text-blue-800 dark:text-blue-300 cursor-pointer">
                                        {{ $canto->titulo }}
                                    </label>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $canto->tipo }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-14 text-center">
                                    <div class="mx-auto mb-3 h-12 w-12 rounded-full bg-blue-50 dark:bg-slate-700 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-500 dark:text-blue-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300 font-medium">Nenhum canto encontrado.</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Ajuste os filtros ou adicione um novo canto.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($cantos, 'links'))
                <div class="mt-4">
                    {{ $cantos->appends(request()->query())->links() }}
                </div>
            @endif

            <!-- Ações -->
            <div class="mt-6 flex justify-end gap-3">
                <!-- Botão PDF -->
                <button type="submit" onclick="document.getElementById('formCantos').action='{{ route('cantos.pdf') }}'"
                        class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    Gerar PDF com Selecionados
                </button>
                <button type="button" @click="scrollTo({top:0, behavior:'smooth'})"
                        class="px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700">
                    Voltar ao topo
                </button>
            </div>
        </form>
    </div>

    <!-- Ajusta o offset do thead sticky -->
    <script>
        (function setHeaderOffset(){
            const header = document.querySelector('header');
            const h = header ? header.offsetHeight : 0;
            document.documentElement.style.setProperty('--app-header', h + 'px');
        })();
        window.addEventListener('resize', () => {
            const header = document.querySelector('header');
            const h = header ? header.offsetHeight : 0;
            document.documentElement.style.setProperty('--app-header', h + 'px');
        });

        function cantosSel() {
            return {
                selected: new Set(),
                allOnPageChecked: false,
                idsOnPage: @json($cantos->pluck('id')),
                init() {
                    const params = new URLSearchParams(window.location.search);
                    const ids = params.getAll('ids[]');
                    if (ids.length) {
                        ids.forEach(id => this.selected.add(Number(id)));
                        this.syncAllOnPage();
                    }
                },
                toggle(id) {
                    this.selected.has(id) ? this.selected.delete(id) : this.selected.add(id);
                    this.syncAllOnPage();
                },
                toggleAll(e) {
                    if (e.target.checked) {
                        this.idsOnPage.forEach(id => this.selected.add(Number(id)));
                    } else {
                        this.idsOnPage.forEach(id => this.selected.delete(Number(id)));
                    }
                    this.allOnPageChecked = e.target.checked;
                },
                clearAll() {
                    this.selected.clear();
                    this.allOnPageChecked = false;
                    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = false);
                },
                syncAllOnPage() {
                    this.allOnPageChecked = this.idsOnPage.every(id => this.selected.has(Number(id)));
                }
            }
        }
    </script>
</x-app-layout>
