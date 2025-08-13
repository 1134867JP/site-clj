<x-app-layout>
  <div class="max-w-6xl mx-auto py-8 px-4">
    <div class="bg-white/70 dark:bg-neutral-900/70 backdrop-blur-xl shadow-2xl rounded-2xl border border-white/10 overflow-hidden">
      <!-- Header + Ações -->
      <div class="px-4 sm:px-6 pt-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $canto->titulo }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $canto->tipos->pluck('nome')->join(', ') ?: '—' }}</p>
          </div>

          <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('cantos.index') }}"
               class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-slate-700 inline-flex items-center gap-2">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
              Voltar
            </a>
            @can('update', $canto)
            <a href="{{ route('cantos.edit', $canto) }}"
               class="px-3 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white inline-flex items-center gap-2">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h6l11-11a2.828 2.828 0 00-4-4L5 17v4z"/></svg>
              Editar
            </a>
            @endcan
          </div>
        </div>
      </div>

      <!-- Toolbar Sticky -->
      <div class="sticky top-[var(--app-header,64px)] z-10 mt-6">
        <div class="px-4 sm:px-6 py-3 bg-white/70 supports-[backdrop-filter]:bg-white/50 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur border-y border-white/10">
          <div class="flex flex-wrap items-center gap-3 sm:gap-4">

            <!-- Tom atual -->
            <div class="flex items-center gap-2">
              <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">Tom</span>
              <span id="current-key" class="text-base font-bold text-amber-600 dark:text-amber-400">{{ $key }}</span>
              <span id="transpose-indicator"
                    class="text-[11px] leading-none font-semibold px-2 py-1 rounded-full border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300">
                ±0
              </span>
            </div>

            <!-- separador -->
            <span class="hidden md:block h-6 w-px bg-gray-200 dark:bg-slate-700"></span>

            <!-- Transpor (segmented) -->
            <div class="inline-flex rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
              <button type="button" onclick="transposeCifra(-1)"
                      class="px-3 py-2 text-sm font-medium bg-gray-50 hover:bg-gray-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                −½ Tom
              </button>
              <span class="w-px bg-gray-200 dark:bg-slate-700" aria-hidden="true"></span>
              <button type="button" onclick="transposeCifra(1)"
                      class="px-3 py-2 text-sm font-medium bg-gray-50 hover:bg-gray-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                +½ Tom
              </button>
              <span class="w-px bg-gray-200 dark:bg-slate-700" aria-hidden="true"></span>
              <button type="button" onclick="resetTranspose()"
                      class="px-3 py-2 text-sm font-medium bg-white hover:bg-gray-50 dark:bg-slate-900 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Reset
              </button>
            </div>

            <!-- separador -->
            <span class="hidden md:block h-6 w-px bg-gray-200 dark:bg-slate-700"></span>

            <!-- Tamanho do texto -->
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-600 dark:text-gray-300">Texto</span>
              <div class="inline-flex items-center overflow-hidden rounded-xl border border-gray-200 dark:border-slate-700">
                <button type="button" onclick="setFontSize(fontPx - 1)"
                        class="px-2 py-1 text-sm hover:bg-gray-50 dark:hover:bg-slate-800">−</button>
                <span id="font-size-label" class="px-2 py-1 text-sm font-semibold w-10 text-center text-gray-800 dark:text-gray-100">16px</span>
                <button type="button" onclick="setFontSize(fontPx + 1)"
                        class="px-2 py-1 text-sm hover:bg-gray-50 dark:hover:bg-slate-800">+</button>
              </div>
            </div>

            <!-- Ações (alinhadas à direita) -->
            <div class="ms-auto flex items-center gap-2">
              <button type="button" onclick="copyCifra()"
                      class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-800 inline-flex items-center gap-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                <span class="hidden sm:inline">Copiar</span>
              </button>
            </div>

          </div>
        </div>
      </div>

      <!-- Cifra -->
      <div class="p-4 sm:p-6">
        <pre id="cifra-letra"
             data-canto-id="{{ $canto->id }}"
             class="bg-white/30 dark:bg-white/5 p-4 rounded-lg text-gray-900 dark:text-gray-100 whitespace-pre-wrap font-mono leading-relaxed text-[16px] border border-white/10 shadow-inner">{!! \App\Http\Controllers\CantosController::formatCifra($canto->letra) !!}</pre>
      </div>
    </div>
  </div>

  <!-- Painel Auto-rolagem — SOMENTE MODOS + PLAY -->
  <div id="asc-panel" class="fixed left-4 bottom-4 z-50 select-none">
    <div class="flex items-center gap-2 rounded-2xl border border-white/10 bg-white/70 supports-[backdrop-filter]:bg-white/50 dark:bg-neutral-900/70 dark:supports-[backdrop-filter]:bg-neutral-900/50 backdrop-blur px-3 py-2 shadow-xl">
      <select id="asc-mode"
              class="rounded-lg border border-white/10 bg-white/40 dark:bg-white/10 text-gray-800 dark:text-gray-100 text-sm font-semibold px-2 py-1">
        <option value="0">Muito Lento</option>
        <option value="1">Lento</option>
        <option value="2" selected>Normal</option>
        <option value="3">Rápido</option>
        <option value="4">Muito Rápido</option>
      </select>

      <button id="asc-play"
              class="h-10 w-10 rounded-xl font-extrabold text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 grid place-items-center">▶</button>
    </div>
  </div>

  <!-- espaço para não cobrir a última linha -->
  <div class="h-32"></div>

  <script>
  // ===== Auto-rolagem (somente modos) — com acumulador fracionado =====
  (function(){
    // velocidades corrigidas (px por segundo)
    const SPEEDS = [
      3,   // 0: Muito Lento
      6,   // 1: Lento
      16,  // 2: Normal
      32,  // 3: Rápido
      64   // 4: Muito Rápido
    ];

    let mode = 2;          // Normal
    let running = false;
    let raf = null;
    let lastTs = 0;
    let carry = 0;         // acumula frações de px entre frames

    const $ = (id) => document.getElementById(id);
    const selMode = $('asc-mode');
    const btnPl   = $('asc-play');
    if (!selMode || !btnPl) return;

    function pxPerSec(){ return SPEEDS[mode] ?? SPEEDS[2]; }

    function atBottom(){
      const el = document.scrollingElement || document.documentElement;
      return (el.clientHeight + el.scrollTop) >= (el.scrollHeight - 1);
    }

    function frame(ts){
      if (!running){ raf = null; return; }
      if (!lastTs) lastTs = ts;
      const dt = (ts - lastTs) / 1000; // segundos
      lastTs = ts;

      // deslocamento real (fracionário) + acumulação
      let dy = pxPerSec() * dt + carry;
      const step = dy | 0;   // parte inteira
      carry = dy - step;     // fração que sobrou

      if (step > 0) {
        if (atBottom()){ toggle(false); return; }
        window.scrollBy(0, step);
      }

      raf = requestAnimationFrame(frame);
    }

    function toggle(force){
      const next = (typeof force === 'boolean') ? force : !running;
      if (next === running) return;
      running = next;
      btnPl.textContent = running ? '⏸' : '▶';
      if (running){
        lastTs = 0;
        carry = 0;
        raf = requestAnimationFrame(frame);
      } else if (raf){
        cancelAnimationFrame(raf);
        raf = null;
      }
    }

    // Eventos
    btnPl.addEventListener('click', () => toggle());
    selMode.addEventListener('change', () => {
      mode = parseInt(selMode.value, 10) || 2;
      // reseta timing para refletir a mudança na hora
      carry = 0;
      lastTs = performance.now();
    });

    // atalhos: espaço play/pause, ← → mudam modo
    window.addEventListener('keydown', (e)=>{
      const tag=(e.target.tagName||'').toUpperCase();
      if (tag==='INPUT'||tag==='TEXTAREA'||e.target.isContentEditable) return;
      if (e.code==='Space'){ e.preventDefault(); toggle(); }
      if (e.key==='ArrowLeft'){
        selMode.selectedIndex = Math.max(0, selMode.selectedIndex-1);
        selMode.dispatchEvent(new Event('change'));
      }
      if (e.key==='ArrowRight'){
        selMode.selectedIndex = Math.min(selMode.options.length-1, selMode.selectedIndex+1);
        selMode.dispatchEvent(new Event('change'));
      }
    });

    document.addEventListener('visibilitychange', ()=>{ if (document.hidden) toggle(false); });
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) toggle(false);

    // init: respeita o "selected" do HTML
    mode = parseInt(selMode.value, 10) || 2;
  })();
  </script>

  <script>
    /* ===== Transposição / Fontes ===== */
    const notesSharp = ['C','C#','D','D#','E','F','F#','G','G#','A','A#','B'];
    const notesFlat  = ['C','Db','D','Eb','E','F','Gb','G','Ab','A','Bb','B'];
    const pre        = document.getElementById('cifra-letra');
    const cantoId    = pre.dataset.cantoId;
    const keyEl      = document.getElementById('current-key');
    const indEl      = document.getElementById('transpose-indicator');
    const fontLabel  = document.getElementById('font-size-label');
    const fontRange  = document.getElementById('font-range');

    let baseHTML   = pre.innerHTML;
    let offset     = Number(localStorage.getItem(`canto:${cantoId}:offset`) || 0);
    let fontPx     = Number(localStorage.getItem(`canto:${cantoId}:font`)  || 16);

    const clamp = (n,min,max)=>Math.min(max,Math.max(min,n));
    const mod   = (n,m)=>((n%m)+m)%m;

    function setFontSize(px){
      fontPx = clamp(px,12,28);
      pre.style.fontSize = fontPx+'px';
      if (fontLabel) fontLabel.textContent = fontPx+'px';
      if (fontRange) fontRange.value = String(fontPx);
      localStorage.setItem(`canto:${cantoId}:font`, String(fontPx));
    }
    fontRange?.addEventListener('input', e=> setFontSize(Number(e.target.value)));

    function transposeChord(chord, semitones){
      const m = chord.match(/^([A-G][b#]?)(.*)$/);
      if (!m) return chord;
      let root=m[1], suffix=m[2]||'';
      let idx=notesSharp.indexOf(root), useSharp=true;
      if(idx===-1){ idx=notesFlat.indexOf(root); useSharp=false; }
      if(idx===-1) return chord;

      const newIdx = mod(idx+semitones,12);
      let newRoot = (useSharp?notesSharp:notesFlat)[newIdx];

      suffix = suffix.replace(/\/([A-G][b#]?)/g,(_,b)=>{
        let bi=notesSharp.indexOf(b), sharp=true;
        if(bi===-1){ bi=notesFlat.indexOf(b); sharp=false; }
        if(bi===-1) return '/'+b;
        const bnew=(sharp?notesSharp:notesFlat)[mod(bi+semitones,12)];
        return '/'+bnew;
      });

      return newRoot+suffix;
    }
    function transposeHTML(html, semitones){
      return html.replace(/<span class="chord">([^<]+)<\/span>/g,(_,c)=>`<span class="chord">${transposeChord(c,semitones)}</span>`);
    }
    function renderFromBase(){
      const eff = mod(offset,12);
      pre.innerHTML = eff===0 ? baseHTML : transposeHTML(baseHTML, eff);
      const pretty = ((offset%12)+12)%12;
      if (indEl) indEl.textContent = (pretty>6?pretty-12:pretty);
    }
    function transposeCifra(semitones){
      offset += semitones;
      renderFromBase();
      updateKey(semitones);
      localStorage.setItem(`canto:${cantoId}:offset`, String(offset));
    }
    function resetTranspose(){
      keyEl.textContent = '{{ $key }}';
      offset = 0;
      renderFromBase();
      localStorage.setItem(`canto:${cantoId}:offset`,'0');
    }
    function updateKey(semitones){
      const key = keyEl.textContent.trim();
      let idx=notesSharp.indexOf(key), useSharp=true;
      if(idx===-1){ idx=notesFlat.indexOf(key); useSharp=false; }
      if(idx!==-1){
        const arr = useSharp?notesSharp:notesFlat;
        keyEl.textContent = arr[(idx+semitones+12)%12];
      }
    }
    async function copyCifra(){
      try{
        const tmp = pre.cloneNode(true);
        tmp.querySelectorAll('span.chord').forEach(s=>s.outerHTML=s.textContent);
        await navigator.clipboard.writeText(tmp.textContent);
      }catch(e){}
    }

    // Ajuste do header sticky e init
    (function setHeaderOffset(){
      const header = document.querySelector('header');
      const h = header ? header.offsetHeight : 64;
      document.documentElement.style.setProperty('--app-header', h + 'px');
    })();

    renderFromBase();
    setFontSize(fontPx);
  </script>

  <style>
    .chord{ color:#d97706; font-weight:700; }
    :root.dark .chord{ color:#f59e0b; }
    @media print{ #asc-panel{ display:none!important; } }
  </style>
</x-app-layout>
