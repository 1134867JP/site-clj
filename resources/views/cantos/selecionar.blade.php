<x-app-layout>
    <div
        x-data="cantosSel()"
        x-init="init()"
        class="max-w-6xl mx-auto py-8 px-4"
    >
        <form method="GET" action="{{ route('cantos.pdf') }}" @submit="prepareAndSubmit($event)">
            <!-- Filtros / Ações -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-3 items-center">
                <div class="flex items-center gap-3">
                    <label for="tipo" class="font-semibold text-gray-800 dark:text-gray-100">Filtrar por tipo:</label>
                    <select name="tipo" id="tipo" onchange="this.form.submit()"
                            class="w-full md:w-auto border border-blue-300 dark:border-slate-600 rounded-xl px-3 py-2 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->nome }}" {{ request('tipo') == $tipo->nome ? 'selected' : '' }}>{{ $tipo->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 flex items-center justify-between gap-3">
                    <div class="flex-1">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por título…"
                               class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-gray-700 dark:text-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit" formaction="{{ route('cantos.selecionar') }}"
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
                            <th class="px-4 py-3 w-14">Sel.</th>
                            <th class="px-4 py-3">Título</th>
                            <th class="px-4 py-3">Tipo</th>
                            <th class="px-4 py-3 w-56 sm:w-64">Tom (alvo)</th>
                            <th class="px-4 py-3 w-44">Capo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse ($cantos as $canto)
                            <tr class="hover:bg-blue-50 dark:hover:bg-slate-700/50 transition">
                                <!-- Sel -->
                                <td class="px-4 py-3">
                                    <input type="checkbox"
                                           :id="'canto-' + {{ $canto->id }}"
                                           name="ids[]"
                                           value="{{ $canto->id }}"
                                           @change="toggle({{ $canto->id }})"
                                           :checked="selected.has({{ $canto->id }})"
                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>

                                <!-- Título -->
                                <td class="px-4 py-3">
                                    <label :for="'canto-' + {{ $canto->id }}"
                                           class="font-semibold text-blue-800 dark:text-blue-300 cursor-pointer">
                                        {{ $canto->titulo }}
                                    </label>
                                </td>

                                <!-- Tipo -->
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                    {{ $canto->tipos->pluck('nome')->join(', ') }}
                                </td>

                                <!-- Tom (alvo) -->
                                <td class="px-4 py-3">
                                  <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 sm:whitespace-nowrap">
                                    <select
                                      class="h-9 min-w-[92px] shrink-0 rounded-xl border border-gray-200 dark:border-slate-600
                                             bg-white dark:bg-slate-800 px-3 text-sm text-gray-700 dark:text-gray-200
                                             focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      :value="targetKey({{ $canto->id }})"
                                      @change="setTargetKey({{ $canto->id }}, $event.target.value)"
                                    >
                                      <option>C</option><option>C#</option><option>D</option><option>D#</option>
                                      <option>E</option><option>F</option><option>F#</option><option>G</option>
                                      <option>G#</option><option>A</option><option>A#</option><option>B</option>
                                    </select>

                                    @if(!empty($canto->tom))
                                      <span class="text-[10px] uppercase tracking-wide px-2 py-0.5 rounded-full border
                                                   bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300
                                                   border-gray-200 dark:border-slate-600">
                                        Base: {{ $canto->tom }}
                                      </span>
                                    @endif

                                    <span
                                      class="inline-flex items-center justify-center h-9 px-3 text-sm font-semibold
                                             rounded-xl border border-gray-200 dark:border-slate-600
                                             text-gray-600 dark:text-gray-300 shrink-0"
                                      x-text="labelOffset({{ $canto->id }})">
                                    </span>
                                  </div>
                                </td>

                                <!-- Capo -->
                                <td class="px-4 py-3">
                                    <div class="inline-flex items-center gap-2">
                                        <div class="inline-flex overflow-hidden rounded-xl border border-gray-200 dark:border-slate-600">
                                            <button type="button"
                                                    @click.prevent="incCapo({{ $canto->id }}, -1)"
                                                    class="px-2 py-1 text-xs hover:bg-gray-50 dark:hover:bg-slate-800">−</button>
                                            <span class="px-2 py-1 text-xs font-semibold w-8 text-center text-gray-800 dark:text-gray-100"
                                                  x-text="getPref({{ $canto->id }}).capo">0</span>
                                            <button type="button"
                                                    @click.prevent="incCapo({{ $canto->id }}, +1)"
                                                    class="px-2 py-1 text-xs hover:bg-gray-50 dark:hover:bg-slate-800">+</button>
                                        </div>
                                        <button type="button"
                                                @click.prevent="setPref({{ $canto->id }}, 0, 0)"
                                                class="px-2 py-1 text-xs rounded-lg border border-gray-200 dark:border-slate-600 text-gray-500 hover:bg-gray-50 dark:hover:bg-slate-800">
                                          limpar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <div class="mx-auto mb-3 h-12 w-12 rounded-full bg-blue-50 dark:bg-slate-700 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-500 dark:text-blue-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
                                        </svg>
                                    </div>

                                    <p class="text-gray-600 dark:text-gray-300 font-medium">Nenhum canto encontrado.</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Ajuste os filtros
                                        @can('create', App\Models\Canto::class)
                                            ou
                                            <a class="text-blue-600 dark:text-blue-300 hover:underline" href="{{ route('cantos.create') }}">adicione um novo canto</a>
                                        @endcan
                                        .
                                    </p>
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
                <button id="btn-pdf" type="submit"
                        :class="selected.size === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                        class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    Gerar PDF com Selecionados
                </button>
                <button type="button" @click="scrollTo({top:0, behavior:'smooth'})"
                        class="px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700">
                    Voltar ao topo
                </button>
            </div>
            <input type="hidden" name="prefs" x-ref="prefs">
        </form>
    </div>

    @php
        // baseMap: tom base por música, normalizado para a tônica (C, C#, Db, …)
        $baseMap = $cantos->mapWithKeys(function($c){
            $base = $c->tom ?: \App\Http\Controllers\CantosController::getKeyFromLetra($c->letra);
            if (!preg_match('/^[A-G][b#]?$/', (string)$base)) {
                if (preg_match('/^([A-G][b#]?)/', (string)$base, $m)) {
                    $base = $m[1];
                } else {
                    $base = 'C';
                }
            }
            return [$c->id => $base];
        });
    @endphp

    <!-- Sticky header offset -->
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

        const observer = new MutationObserver(() => {
            const header = document.querySelector('header');
            const h = header ? header.offsetHeight : 0;
            document.documentElement.style.setProperty('--app-header', h + 'px');
        });
        const _header = document.querySelector('header');
        if (_header) observer.observe(_header, { attributes: true, childList: true, subtree: true });

        document.addEventListener('DOMContentLoaded', () => {
            const appLayout = document.querySelector('.max-w-6xl');
            if (appLayout) appLayout.style.marginTop = 'var(--app-header, 0px)';
        });

        function cantosSel() {
          return {
            selected: new Set(),
            allOnPageChecked: false,
            idsOnPage: @json($cantos->pluck('id')),
            prefs: {},

            // tom base normalizado por id
            baseMap: @json($baseMap),

            idxMap: {
              'C':0,'C#':1,'Db':1,'D':2,'D#':3,'Eb':3,'E':4,'F':5,'F#':6,'Gb':6,
              'G':7,'G#':8,'Ab':8,'A':9,'A#':10,'Bb':10,'B':11
            },
            keysDisp: ['C','C#','D','D#','E','F','F#','G','G#','A','A#','B'],

            mod(n,m){ return ((n % m) + m) % m; },
            keyIndex(k){ return this.idxMap[k] ?? -1; },
            transpose(base, off){
              const bi = this.keyIndex(base);
              if (bi < 0) return base || 'C';
              const idx = this.mod(bi + (off||0), 12);
              return this.keysDisp[idx];
            },

            targetKey(id){
              const base = this.baseMap[id] || 'C';
              const off  = this.getPref(id).offset;
              return this.transpose(base, off);
            },
            setTargetKey(id, key){
              const base = this.baseMap[id] || 'C';
              const bi = this.keyIndex(base);
              const ti = this.keyIndex(key);
              if (bi < 0 || ti < 0) return;
              const off = this.mod(ti - bi, 12);
              const p = this.getPref(id);
              this.setPref(id, off, p.capo);
            },

            init() {
              const params = new URLSearchParams(window.location.search);
              const ids = params.getAll('ids[]');
              if (ids.length) {
                ids.forEach(id => this.selected.add(Number(id)));
                this.syncAllOnPage();
              }
              const b64 = params.get('prefs');
              if (b64) {
                try {
                  const arr = JSON.parse(atob(b64.replace(/-/g,'+').replace(/_/g,'/')));
                  arr.forEach(p => this.setPref(Number(p.id), Number(p.offset||0), Number(p.capo||0)));
                } catch {}
              }
            },

            toggle(id) {
              this.selected.has(id) ? this.selected.delete(id) : this.selected.add(id);
              this.syncAllOnPage();
            },
            toggleAll(e) {
              if (e.target.checked) this.idsOnPage.forEach(id => this.selected.add(Number(id)));
              else this.idsOnPage.forEach(id => this.selected.delete(Number(id)));
              this.allOnPageChecked = e.target.checked;
            },
            clearAll() {
              this.selected.clear();
              this.allOnPageChecked = false;
              document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = false);
            },
            syncAllOnPage() {
              this.allOnPageChecked = this.idsOnPage.every(id => this.selected.has(Number(id)));
            },

            getPref(id) {
              if (!this.prefs[id]) this.prefs[id] = { offset: 0, capo: 0 };
              return this.prefs[id];
            },
            setPref(id, off, cap) {
              const offEff = ((Number(off)||0) % 12 + 12) % 12;
              const capEff = Math.max(0, Math.min(12, Number(cap)||0));
              this.prefs[id] = { offset: offEff, capo: capEff };
            },
            incCapo(id, d) {
              const p = this.getPref(id);
              this.setPref(id, p.offset, p.capo + d);
            },
            labelOffset(id) {
              const v = this.getPref(id).offset;
              const pretty = v > 6 ? v - 12 : v;
              return (pretty >= 0 ? '+' : '') + pretty;
            },

            prepareAndSubmit($event) {
              if (this.selected.size === 0) { $event.preventDefault(); return; }

              const payload = Array.from(this.selected).map(id => {
                const p = this.getPref(id);
                return { id, offset: p.offset, capo: p.capo };
              });

              const b64url = btoa(JSON.stringify(payload))
                .replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/,'');

              this.$refs.prefs.value = b64url;
            },
          }
        }
    </script>
</x-app-layout>
