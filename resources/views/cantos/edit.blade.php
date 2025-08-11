<x-app-layout>
  <div class="max-w-6xl mx-auto py-8 px-4">
    <div class="bg-white dark:bg-slate-900 shadow rounded-2xl border border-gray-100 dark:border-slate-800 overflow-hidden">
      <div class="px-6 pt-6 pb-2 flex items-center justify-between gap-4">
        <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Editar Canto</h1>

        <div class="flex items-center gap-2">
          <a href="{{ route('cantos.index') }}"
             class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-slate-700">
            Cancelar
          </a>
          <button form="form-canto" type="submit"
                  class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold">
            Atualizar
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
        <form id="form-canto" method="POST" action="{{ route('cantos.update', $canto) }}"
              class="space-y-5">
          @csrf
          @method('PUT')

          <div>
            <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-100">Título</label>
            <input type="text" name="titulo"
                   value="{{ old('titulo', $canto->titulo) }}"
                   required
                   class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
          </div>

          <div>
            <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-100">Tipo</label>
            <select name="canto_tipo_id" required
                    class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
              <option value="">Selecione o tipo</option>
              @foreach($tipos as $tipo)
                <option value="{{ $tipo->id }}" {{ (string)old('canto_tipo_id', $canto->canto_tipo_id) === (string)$tipo->id ? 'selected' : '' }}>
                  {{ $tipo->nome }}
                </option>
              @endforeach
            </select>
          </div>

          <div>
            <label class="block font-semibold mb-1 text-gray-800 dark:text-gray-100">Letra / Cifra</label>
            <textarea id="letra" name="letra" rows="12" required
                      class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-gray-900 dark:text-gray-100 font-mono leading-relaxed resize-y focus:ring-2 focus:ring-blue-500"
                      placeholder="Ex:
[G] Senhor, eu sei que é teu este lugar
[C] Todos querem te adorar"
            >{{ old('letra', $canto->letra) }}</textarea>

            <div class="mt-1 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
              <span id="contador">0 chars • 0 linhas</span>
              <span>Dica: acorde no início da linha ex.: <code class="font-mono">[G]</code> ou <code class="font-mono">C#m7/G#</code></span>
            </div>
          </div>

          <div class="flex items-center justify-between pt-1">
            <div class="text-sm text-gray-500 dark:text-gray-400">
              Tom detectado: <span id="key-detect" class="font-semibold">—</span>
            </div>
            <div class="flex gap-2">
              <button type="button" id="btn-simplificar"
                      class="px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-800">
                Simplificar cifra (β)
              </button>
              <button type="button" id="btn-formatar"
                      class="px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-800">
                Formatar espaçamento
              </button>
            </div>
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
                 class="bg-white dark:bg-slate-900 p-4 rounded-lg text-gray-900 dark:text-gray-100 whitespace-pre-wrap font-mono leading-relaxed text-[14px] border border-gray-200 dark:border-slate-800 shadow-inner min-h-[240px]"></pre>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- JS --}}
  <script>
    const textarea  = document.getElementById('letra');
    const preview   = document.getElementById('preview');
    const contador  = document.getElementById('contador');
    const keyDetect = document.getElementById('key-detect');

    function detectKey(text) {
      const m = text.match(/^\s*(?:\[)?([A-G][b#]?)/m);
      return m ? m[1] : 'C';
    }

    function escapeHTML(s){
      const div = document.createElement('div');
      div.textContent = s;
      return div.innerHTML;
    }

    function wrapChords(text) {
      const escaped = escapeHTML(text);
      const chordRe =
        /(^|[\s(])([A-G](?:#|b)?(?:maj7|maj9|maj11|maj13|m7|m9|m11|m13|maj|min|m|dim|aug|sus2|sus4|add9|add11|add13|6|7|9|11|13)?(?:\([^\)]*\))?(?:\/[A-G](?:#|b)?(?:m7|m|7|9|11|13)?)?)(?=$|\s|[),.;:])(?![a-zà-úâêîôûãõç])/gmu;

      return escaped.replace(chordRe, (full, lead, chord) => {
        return lead + '<span class="chord">' + chord + '</span>';
      });
    }

    function updatePreview() {
      const val = textarea.value;
      preview.innerHTML = wrapChords(val);
      const linhas = val.length ? val.split(/\r?\n/).length : 0;
      contador.textContent = `${val.length} chars • ${linhas} linhas`;
      keyDetect.textContent = detectKey(val);
    }

    document.getElementById('btn-formatar').addEventListener('click', () => {
      textarea.value = textarea.value.replace(/[ \t]+$/gm, '').replace(/\r\n/g, '\n');
      updatePreview();
    });

    document.getElementById('btn-simplificar').addEventListener('click', () => {
      const map = { 'Db':'C#','Eb':'D#','Gb':'F#','Ab':'G#','Bb':'A#' };
      textarea.value = textarea.value.replace(/\b(C#|Db|D#|Eb|F#|Gb|G#|Ab|A#|Bb)\b/g, (m) => map[m] ?? m);
      updatePreview();
    });

    textarea.addEventListener('input', updatePreview);
    updatePreview();

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
