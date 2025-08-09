<x-app-layout>
  <div class="max-w-6xl mx-auto py-8 px-4">
    <div class="bg-white dark:bg-slate-900 shadow rounded-2xl border border-gray-100 dark:border-slate-800 overflow-hidden">
      <!-- Header + Ações -->
      <div class="px-4 sm:px-6 pt-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $canto->titulo }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $canto->tipo ?? '—' }}</p>
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
        <div class="px-4 sm:px-6 py-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur border-y border-gray-100 dark:border-slate-800">
          <div class="flex flex-wrap items-center gap-4">
            <!-- Tom atual -->
            <div class="flex items-center gap-2">
              <span class="font-semibold text-gray-700 dark:text-gray-200">Tom:</span>
              <span id="current-key" class="font-bold text-amber-600 dark:text-amber-400">{{ $key }}</span>
              <span id="transpose-indicator" class="text-xs px-2 py-0.5 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300">±0</span>
            </div>

            <!-- Transpor -->
            <div class="flex items-center gap-2">
              <button type="button" onclick="transposeCifra(-1)"
                      class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-800 dark:text-gray-100">
                −½ Tom
              </button>
              <button type="button" onclick="transposeCifra(1)"
                      class="px-3 py-2 rounded-lg bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-800 dark:text-gray-100">
                +½ Tom
              </button>
              <button type="button" onclick="resetTranspose()"
                      class="px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-800">
                Reset
              </button>
            </div>

            <!-- Tamanho -->
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-600 dark:text-gray-300">Texto</span>
              <input id="font-range" type="range" min="12" max="28" step="1" class="accent-blue-600">
              <span id="font-size-label" class="text-sm text-gray-600 dark:text-gray-300">16px</span>
            </div>

            <!-- Capotraste -->
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-600 dark:text-gray-300">Capo</span>
              <button type="button" id="capo-dec"
                      class="px-2 py-1 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-800">−</button>
              <span id="capo-label" class="text-sm font-semibold text-gray-800 dark:text-gray-100">0</span>
              <button type="button" id="capo-inc"
                      class="px-2 py-1 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-800">+</button>
              <button type="button" id="capo-clear"
                      class="px-2 py-1 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-500 hover:bg-gray-50 dark:hover:bg-slate-800">limpar</button>
            </div>

            <!-- Utilitários -->
            <div class="flex items-center gap-2 ms-auto">
              <button type="button" onclick="copyCifra()"
                      class="px-3 py-2 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-800">
                Copiar
              </button>

              <div class="flex items-center gap-2">
                <span id="saved-badge"
                      class="text-xs px-2 py-1 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300">
                  Tom salvo: —
                </span>
                <button type="button" id="save-pdf-btn"
                        class="px-3 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">
                  Salvar p/ PDF
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Cifra -->
      <div class="p-4 sm:p-6">
        <pre id="cifra-letra"
             data-canto-id="{{ $canto->id }}"
             class="bg-white dark:bg-slate-900 p-4 rounded-lg text-gray-900 dark:text-gray-100 whitespace-pre-wrap font-mono leading-relaxed text-[16px] border border-gray-200 dark:border-slate-800 shadow-inner">{!! \App\Http\Controllers\CantosController::formatCifra($canto->letra) !!}</pre>
      </div>
    </div>
  </div>

  <!-- Painel Auto-rolagem (mini, fixo no canto inferior esquerdo) -->
  <div id="asc-panel" class="asc-panel-mini" aria-live="polite">
    <button id="asc-toggle" class="asc-btn asc-play" title="Iniciar / Pausar">▶</button>

    <div class="asc-meter" id="asc-meter">
      <span id="asc-meter-text">30</span>
    </div>

    <div class="asc-step">
      <button id="asc-slower" class="asc-btn" title="Velocidade −">−</button>
      <button id="asc-faster" class="asc-btn" title="Velocidade +">+</button>
    </div>
  </div>

  <script>
    /* ===== ELEMENTOS & ESTADO ===== */
    const notesSharp = ['C','C#','D','D#','E','F','F#','G','G#','A','A#','B'];
    const notesFlat  = ['C','Db','D','Eb','E','F','Gb','G','Ab','A','Bb','B'];
    const pre        = document.getElementById('cifra-letra');
    const cantoId    = pre.dataset.cantoId;
    const keyEl      = document.getElementById('current-key');
    const indEl      = document.getElementById('transpose-indicator');
    const fontLabel  = document.getElementById('font-size-label');
    const fontRange  = document.getElementById('font-range');

    const badge      = document.getElementById('saved-badge');
    const saveBtn    = document.getElementById('save-pdf-btn');

    // Capo
    const capoLabel  = document.getElementById('capo-label');
    const capoDec    = document.getElementById('capo-dec');
    const capoInc    = document.getElementById('capo-inc');
    const capoClear  = document.getElementById('capo-clear');

    // Painel mini
    const ascPanel    = document.getElementById('asc-panel');
    const ascToggle   = document.getElementById('asc-toggle');
    const ascSlower   = document.getElementById('asc-slower');
    const ascFaster   = document.getElementById('asc-faster');
    const ascMeter    = document.getElementById('asc-meter');
    const ascMeterTxt = document.getElementById('asc-meter-text');

    const stateKey   = (k) => `canto:${cantoId}:${k}`;
    const keyPdf     = ()  => `canto:${cantoId}:pdf_offset`;   // 0..11
    const keyCapo    = ()  => `canto:${cantoId}:capo`;         // 0..12
    const keyCapoPdf = ()  => `canto:${cantoId}:pdf_capo`;     // 0..12

    let baseHTML   = pre.innerHTML;
    let offset     = Number(localStorage.getItem(stateKey('offset')) || 0);
    let fontPx     = Number(localStorage.getItem(stateKey('font'))  || 16);
    let speed      = Number(localStorage.getItem(stateKey('speed')) || 30);
    let autoRun    = (localStorage.getItem(stateKey('auto')) === '1');
    let capo       = Number(localStorage.getItem(keyCapo()) || 0);

    const clamp = (n,min,max)=>Math.min(max,Math.max(min,n));
    const mod   = (n,m)=>((n%m)+m)%m;

    /* ===== UI BÁSICA ===== */
    function setFontSize(px){
      fontPx = clamp(px,12,28);
      pre.style.fontSize = fontPx+'px';
      fontLabel.textContent = fontPx+'px';
      if (fontRange) fontRange.value = String(fontPx);
      localStorage.setItem(stateKey('font'), String(fontPx));
    }
    fontRange?.addEventListener('input', e=> setFontSize(Number(e.target.value)));

    function setCapo(v){
      capo = clamp(Number(v)||0,0,12);
      capoLabel.textContent = String(capo);
      localStorage.setItem(keyCapo(), String(capo));
      showUnsavedHint();
    }
    capoDec?.addEventListener('click',()=>setCapo(capo-1));
    capoInc?.addEventListener('click',()=>setCapo(capo+1));
    capoClear?.addEventListener('click',()=>setCapo(0));
    setCapo(capo);

    /* ===== TRANSPOSIÇÃO ===== */
    function transposeChord(chord, semitones){
      const m = chord.match(/^([A-G][b#]?)(.*)$/);
      if (!m) return chord;
      let root=m[1], suffix=m[2]||'';
      let idx=notesSharp.indexOf(root), useSharp=true;
      if(idx===-1){ idx=notesFlat.indexOf(root); useSharp=false; }
      if(idx===-1) return chord;

      const newIdx = mod(idx+semitones,12);
      let newRoot = (useSharp?notesSharp:notesFlat)[newIdx];

      // (corrigido) baixo /X
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
      indEl.textContent = (pretty>6?pretty-12:pretty);
      showUnsavedHint();
    }
    function transposeCifra(semitones){
      offset += semitones;
      renderFromBase();
      updateKey(semitones);
      localStorage.setItem(stateKey('offset'), String(offset));
    }
    function resetTranspose(){
      keyEl.textContent = '{{ $key }}';
      offset = 0;
      renderFromBase();
      localStorage.setItem(stateKey('offset'),'0');
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

    /* ===== SALVAR P/ PDF ===== */
    function updateSavedBadge(){
      const savedOff  = localStorage.getItem(keyPdf());
      const savedCapo = localStorage.getItem(keyCapoPdf());
      if(savedOff===null && savedCapo===null){
        badge.textContent='Tom salvo: —';
        badge.classList.remove('bg-green-50');
        return;
      }
      const n = Number(savedOff ?? 0);
      const label = (n>6?n-12:n);
      const capoTxt = savedCapo ? ` | Capo: ${savedCapo}` : '';
      badge.textContent = 'Tom salvo: '+(label>=0?('+'+label):label)+capoTxt;
      badge.classList.add('bg-green-50');
    }
    function showUnsavedHint(){
      const savedOff  = Number(localStorage.getItem(keyPdf()));
      const savedCapo = Number(localStorage.getItem(keyCapoPdf()));
      const curOff    = mod(offset,12);
      const changed   = (Number.isNaN(savedOff)||savedOff!==curOff) || (Number.isNaN(savedCapo)||savedCapo!==capo);
      if(changed){
        badge.textContent='Tom salvo: (alterado)';
        badge.classList.remove('bg-green-50');
      }else updateSavedBadge();
    }
    function saveOffsetForPdf(){
      localStorage.setItem(keyPdf(), String(mod(offset,12)));
      localStorage.setItem(keyCapoPdf(), String(capo));
      updateSavedBadge();
      const t=saveBtn.textContent; saveBtn.textContent='Salvo!'; setTimeout(()=>saveBtn.textContent=t,900);
    }
    saveBtn.addEventListener('click', saveOffsetForPdf);

    /* ===== AUTO-ROLAGEM (mini) ===== */
    let raf=null;
    const SPEED_MIN = 10, SPEED_MAX = 80, STEP = 5;

    function step(){ window.scrollBy({top:speed/10,behavior:'smooth'}); raf=requestAnimationFrame(step); }
    function startAuto(){ if(raf) return; raf=requestAnimationFrame(step); localStorage.setItem(stateKey('auto'),'1'); ascSync(); }
    function stopAuto(){ if(raf) cancelAnimationFrame(raf); raf=null; localStorage.setItem(stateKey('auto'),'0'); ascSync(); }
    function setSpeed(v){
      speed = clamp(v,SPEED_MIN,SPEED_MAX);
      localStorage.setItem(stateKey('speed'), String(speed));
      ascSync();
    }

    ascToggle.addEventListener('click', ()=> raf?stopAuto():startAuto());
    ascSlower.addEventListener('click', ()=> setSpeed(speed-STEP));
    ascFaster.addEventListener('click', ()=> setSpeed(speed+STEP));

    window.addEventListener('keydown', (e)=>{
      const tag=(e.target.tagName||'').toUpperCase();
      if(tag==='INPUT'||tag==='TEXTAREA'||e.target.isContentEditable) return;
      if(e.code==='Space'){ e.preventDefault(); raf?stopAuto():startAuto(); }
      else if(e.key==='ArrowLeft'){ e.preventDefault(); setSpeed(speed-STEP); }
      else if(e.key==='ArrowRight'){ e.preventDefault(); setSpeed(speed+STEP); }
    });

    function ascSync(){
      ascToggle.textContent = raf?'⏸':'▶';
      ascMeterTxt.textContent = String(speed);
      const p = (speed-SPEED_MIN)/(SPEED_MAX-SPEED_MIN); // 0..1
      ascMeter.style.setProperty('--p', p);
    }

    /* ===== INIT ===== */
    (function setHeaderOffset(){
      const header = document.querySelector('header');
      const h = header ? header.offsetHeight : 64;
      document.documentElement.style.setProperty('--app-header', h + 'px');
    })();

    renderFromBase();
    setFontSize(fontPx);
    setSpeed(speed);
    if(autoRun) startAuto();
    updateSavedBadge();
    ascSync();
  </script>

  <style>
    .chord{ color:#d97706; font-weight:700; }
    :root.dark .chord{ color:#f59e0b; }

    /* ----- Painel mini, fixo no canto ----- */
    .asc-panel-mini{
      position: fixed;
      left: 16px;
      bottom: 16px;
      z-index: 60;
      display: grid;
      grid-template-columns: 44px 80px 60px;
      align-items: center;
      gap: 8px;

      padding: 10px;
      background: rgba(255,255,255,.85);
      border: 1px solid rgba(226,232,240,.7);
      backdrop-filter: blur(8px);
      border-radius: 14px;
      box-shadow: 0 14px 30px rgba(0,0,0,.14);
    }
    :root.dark .asc-panel-mini{
      background: rgba(2,6,23,.86);
      border-color: #334155;
    }

    .asc-btn{
      display:flex; align-items:center; justify-content:center;
      height:36px; min-width:36px; border-radius:10px; cursor:pointer;
      border:1px solid #e5e7eb; background:#f9fafb; color:#111827; font-weight:800;
    }
    .asc-play{ background:#2563eb; color:#fff; border-color:#1e40af; }
    :root.dark .asc-btn{ background:#0b1220; border-color:#334155; color:#e5e7eb; }
    :root.dark .asc-play{ background:#1d4ed8; border-color:#1e3a8a; }

    /* medidor circular compacto */
    .asc-meter{
      --p:.25;
      width: 64px; height: 64px; border-radius: 9999px;
      background: conic-gradient(#6366f1 calc(var(--p)*360deg), #e5e7eb 0);
      border: 5px solid #fff;
      box-shadow: inset 0 0 0 5px #f1f5f9, 0 4px 12px rgba(0,0,0,.08);
      display:flex; align-items:center; justify-content:center;
      margin: 0 auto;
    }
    .asc-meter span{ font-weight:800; font-size:14px; color:#111827; }
    :root.dark .asc-meter{
      border-color:#0b1220; box-shadow: inset 0 0 0 5px #1f2937, 0 4px 12px rgba(0,0,0,.22);
      background: conic-gradient(#818cf8 calc(var(--p)*360deg), #334155 0);
    }
    :root.dark .asc-meter span{ color:#e5e7eb; }

    .asc-step{ display:flex; gap:8px; justify-content:flex-end; }
  </style>
</x-app-layout>
