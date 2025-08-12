<x-app-layout>
  {{-- tipos agora vêm do controller como coleção de CantoTipo --}}

  <div class="max-w-6xl mx-auto py-8 px-4">
    <div class="bg-white dark:bg-slate-900 shadow rounded-2xl border border-gray-100 dark:border-slate-800 overflow-hidden">
      <div class="px-6 pt-6 pb-2 flex items-center justify-between gap-4">
        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Novo Canto</h1>

        <div class="flex items-center gap-2">
          <a href="{{ route('cantos.index') }}"
             class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-slate-700">
            Cancelar
          </a>
          <button form="form-canto" type="submit"
                  class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
            Salvar
          </button>
        </div>
      </div>

      {{-- Erros de validação --}}
      @if ($errors->any())
        <div class="mx-6 mb-4 rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/30 text-red-800 dark:text-red-200 p-4">
          <div class="font-semibold mb-1">Ops, confira os campos:</div>
          <ul class="list-disc ms-5 text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-6 px-6 pb-6">
        {{-- FORM --}}
        <form id="form-canto" method="POST" action="{{ route('cantos.store') }}" class="space-y-5">
          @csrf

          <div>
            <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-100">Título</label>
            <input type="text" name="titulo" value="{{ old('titulo') }}" required
                   class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
            @error('titulo')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-100">Tipos</label>
            <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-2">
              <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                @foreach($tipos as $tipo)
                  <label class="inline-flex items-center gap-2 text-sm text-gray-800 dark:text-gray-100">
                    <input type="checkbox" name="tipos[]" value="{{ $tipo->id }}"
                           @checked(in_array($tipo->id, old('tipos', [])))
                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span>{{ $tipo->nome }}</span>
                  </label>
                @endforeach
              </div>
            </div>
            @error('tipos')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-100">Letra / Cifra</label>

            {{-- Toolbar do textarea --}}
            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
              <div class="flex items-center gap-2">
                <button type="button" id="btn-est" class="px-2 py-1 text-xs rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800">
                  + Estrofe
                </button>
                <button type="button" id="btn-ref" class="px-2 py-1 text-xs rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800">
                  + Refrão
                </button>
                <button type="button" id="btn-colar" class="px-2 py-1 text-xs rounded-lg border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800">
                  Colar (limpo)
                </button>
              </div>
              <div class="flex items-center gap-1">
                <button type="button" id="btn-plus"  class="bg-gray-200 dark:bg-slate-700 rounded px-2 text-xs hover:bg-gray-300 dark:hover:bg-slate-600">+ linhas</button>
                <button type="button" id="btn-minus" class="bg-gray-200 dark:bg-slate-700 rounded px-2 text-xs hover:bg-gray-300 dark:hover:bg-slate-600">– linhas</button>
              </div>
            </div>

            <textarea id="letra" name="letra" rows="14" required
                      class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-gray-900 dark:text-gray-100 font-mono leading-relaxed resize-y focus:ring-2 focus:ring-blue-500"
                      style="tab-size: 4;"
                      placeholder="Ex:
[F#] Chuva de arroz, rosas no buquê
[Ebm] Deixa pra depois, pra quem merecer"
            >{{ old('letra') }}</textarea>

            <div class="mt-1 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
              <span id="contador">0 chars • 0 linhas</span>
              <span>Dica: acorde no início da linha ex.: <code class="font-mono">[G]</code> ou <code class="font-mono">C#m7/G#</code></span>
            </div>

            @error('letra')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </form>

        {{-- PREVIEW --}}
        <div class="mt-6 md:mt-0">
          <div class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 p-4">
            <div class="flex items-center justify-between mb-2">
              <h2 class="font-semibold text-gray-800 dark:text-gray-100">Preview</h2>
              <span class="text-xs px-2 py-0.5 rounded-lg border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-gray-300">Ao vivo</span>
            </div>
            <pre id="preview"
                 class="bg-white dark:bg-slate-900 p-4 rounded-lg text-gray-900 dark:text-gray-100 whitespace-pre-wrap font-mono leading-relaxed text-[14px] border border-gray-200 dark:border-slate-800 shadow-inner min-h-[260px]"></pre>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- JS --}}
  <script>
    const txt   = document.getElementById('letra');
    const prev  = document.getElementById('preview');
    const cnt   = document.getElementById('contador');

    // botões
    document.getElementById('btn-plus').onclick  = () => txt.rows = Math.min(txt.rows + 2, 80);
    document.getElementById('btn-minus').onclick = () => txt.rows = Math.max(txt.rows - 2, 6);

    // templates
    document.getElementById('btn-est').onclick = () => insertTemplate('[C]\nLetra da estrofe...\n\n');
    document.getElementById('btn-ref').onclick = () => insertTemplate('[F]\nLetra do refrão...\n\n');

    // colar limpando formatação
    document.getElementById('btn-colar').onclick = async () => {
      try {
        const data = await navigator.clipboard.readText();
        insertAtCursor(cleanPaste(data));
      } catch (_) {}
    };

    function cleanPaste(s){
      // Normaliza quebras, remove espaços à direita
      return s.replace(/\r\n/g, '\n').replace(/[ \t]+$/gm, '');
    }
    function insertAtCursor(s){
      const start = txt.selectionStart, end = txt.selectionEnd;
      txt.value = txt.value.slice(0, start) + s + txt.value.slice(end);
      txt.selectionStart = txt.selectionEnd = start + s.length;
      updatePreview();
    }
    function insertTemplate(t){ insertAtCursor(t); }

    function escapeHTML(s){
      const div = document.createElement('div');
      div.textContent = s;
      return div.innerHTML;
    }

    // Regex de acordes
    const chordRe = /(^|[\s(])(\[[A-G](?:#|b)?\]|[A-G](?:#|b)?(?:maj7|maj9|maj11|maj13|m7|m9|m11|m13|maj|min|m|dim|aug|sus2|sus4|add9|add11|add13|6|7|9|11|13)?(?:\([^\)]*\))?(?:\/[A-G](?:#|b)?(?:m|7|9|11|13)?)?)(?=$|\s|[),.;:])(?![a-zà-úâêîôûãõç])/gmu;

    function wrapChords(text) {
      const escaped = escapeHTML(text);
      return escaped.replace(chordRe, (full, lead, chord) => lead + '<span class="chord">' + chord + '</span>');
    }

    function updatePreview() {
      const v = txt.value;
      prev.innerHTML = wrapChords(v);
      const lines = v.length ? v.split(/\r?\n/).length : 0;
      cnt.textContent = `${v.length} chars • ${lines} linhas`;
    }

    txt.addEventListener('input', updatePreview);
    updatePreview();

    // Atalho Ctrl/Cmd+S para enviar o form
    window.addEventListener('keydown', (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
        e.preventDefault();
        document.getElementById('form-canto').submit();
      }
    });
  </script>

  <style>
    .chord{ color:#d97706; font-weight:700; }
    :root.dark .chord{ color:#f59e0b; }
    code{ background:transparent; }
  </style>
</x-app-layout>
