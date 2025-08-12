<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cantos Missa CLJ</title>
  <style>
    /* Reserva espaço para o cabeçalho fixo (alinhado com o header.top) */
    @page { margin: 72px 20px 24px 20px; }
    * { box-sizing: border-box; }
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12.5px;
      line-height: 1.3; /* letras mais juntas */
      color: #111827;
      margin: 0; /* remove margem default que pode empurrar o conteúdo */
    }

    /* ===== Cabeçalho fixo ===== */
    .header {
      position: fixed;
      top: -72px; left: 0; right: 0;  /* igual ao @page margin-top */
      height: 60px; /* um pouco menor para caber com folga */
      z-index: 10;
    }
    .header-table { width: 100%; border-collapse: collapse; }
    .header-table td { vertical-align: middle; }
    .h-left { width: 72px; }
    .h-right { width: 72px; text-align: right; }
    .brand {
      font-weight: 800;
      text-transform: uppercase;
      text-align: center;
      font-size: 14px; /* ligeiramente menor */
      color: #0f172a;
      letter-spacing: .25px;
      white-space: nowrap;
    }
    .logo { max-height: 28px; width: auto; display: inline-block; }
    .hr { border: 0; border-top: 1px solid #cbd5e1; margin: 2px 0 0; }

    /* ===== Conteúdo ===== */
    .canto    { margin-bottom: 10px; }
    .titulo   {
      font-weight: 700; font-size: 12.5px; text-transform: uppercase; color: #0f172a;
      border-bottom: 1px solid #cbd5e1; padding-bottom: 2px; margin-bottom: 4px;
      page-break-after: avoid; /* mantém o título com o próximo bloco */
    }

    /* Cada estrofe vira um bloco, mas pode quebrar para não sobrar buraco no fim da página */
    .letra    { border: 1px solid #e2e8f0; background: #f1f5f9; border-radius: 4px; padding: 4px 6px; }
    .estrofe  {
      white-space: pre-wrap;
      font-family: DejaVu Sans Mono, DejaVu Sans, monospace;
      font-size: 12.5px; line-height: 1.35;
      margin: 2px 0;
      page-break-inside: auto; /* permitir dividir estrofe longa */
      break-inside: auto;
      orphans: 2; widows: 2;  /* evita 1 linha perdida no topo/rodapé da página */
    }
    .chord { color: #d97706; font-weight: 700; }

    /* ===== Rodapé ===== */
    .footer {
      position: fixed; bottom: -24px; left: 0; right: 0; /* igual ao @page margin-bottom */
      color: #64748b; font-size: 10.5px; text-align: right;
    }
    .pagenum:before { content: counter(page); }
  </style>
</head>
<body>

  {{-- Cabeçalho com logos + título --}}
  <div class="header">
    <table class="header-table">
      <tr>
        <td class="h-left">
          @php
            // Caminho real para checagem e URI file:// para o Dompdf
            $logoLeftPath = public_path('storage/images/clj_logo_left.png');
            $logoLeft = 'file://'.str_replace('\\','/', $logoLeftPath);
          @endphp
          @if (file_exists($logoLeftPath))
            <img class="logo" src="{{ $logoLeft }}" alt="CLJ">
          @endif
        </td>
        <td class="brand">CANTOS MISSA CLJ</td>
        <td class="h-right">
          @php
            $logoRightPath = public_path('storage/images/clj_logo_right.png');
            $logoRight = 'file://'.str_replace('\\','/', $logoRightPath);
          @endphp
          @if (file_exists($logoRightPath))
            <img class="logo" src="{{ $logoRight }}" alt="CLJ">
          @endif
        </td>
      </tr>
    </table>
    <hr class="hr">
  </div>

  <div class="wrap">
    @php
      // --- Helpers para PDF ---
      if (!function_exists('formatChordSpans')) {
             function formatChordSpans($texto) {
        // Raiz APENAS maiúscula fora de colchetes
        $ROOT      = '[A-G](?:[#b]|♭|♯)?';
        $QUAL_OPT  = '(?:maj7|maj9|maj11|maj13|maj|min|m|dim7?|aug|sus2|sus4|sus|add(?:2|4|9|11|13)?|2|4|5|6|7|9|11|13)?';
        $QUAL_STR  = '(?:maj7|maj9|maj11|maj13|maj|min|dim7?|aug|sus2|sus4|sus|add(?:2|4|9|11|13)?|2|4|5|6|7|9|11|13)'; // sem 'm'
        $PAREN     = '(?:\([^\)]*\))?';
        $BASS      = '(?:\/[A-G](?:[#b]|♭|♯)?(?:m|maj7|7|9|11|13)?)?';

        // Permitir variante forte "M" apenas para notas com acidente (#/b): ex.: C#M, DbM
        $CORE_BASE    = $ROOT.$QUAL_OPT.$PAREN.$BASS;               // ex: E, C#m, A9/E
        $CORE_M_ACC   = '[A-G](?:[#b]|♭|♯)M'.$PAREN.$BASS;          // ex: C#M, DbM, C#M/E
        $CORE         = '(?:'.$CORE_BASE.'|'.$CORE_M_ACC.')';
        $CORE_STRONG  = '(?:'.$ROOT.$QUAL_STR.$PAREN.$BASS.'|'.$CORE_M_ACC.')'; // forte (inclui M com acidente)

        // sem flag 'i' (case-insensitive) para não pegar 'em'
        $RE_BRACKET = '/\[(('.$CORE.'))\]/u';
        $RE_GEN     = '/(^|[\s(])('.$CORE.')(?=$|\s|[),.;:])/u';
        $RE_STR     = '/(^|[\s(])('.$CORE_STRONG.')(?=$|\s|[),.;:])/u';

        $out = [];
        foreach (preg_split("/\R/u", (string)$texto) as $raw) {
            $escaped = e($raw);

            // Detecta se a linha é de cifra (não deixa 'Em' em português pesar)
            $bc = preg_match_all($RE_BRACKET, $raw);
            $sc = preg_match_all($RE_STR,     $raw);
            $gc = preg_match_all($RE_GEN,     $raw);
            $general = $bc + $sc + $gc;

            $stripped = preg_replace([$RE_BRACKET, $RE_STR, $RE_GEN], ' ', $raw);
            $lettersLeft = preg_match('/\pL/u', $stripped) ? 1 : 0;

            $isChordLine = ($bc + $sc >= 1) || ($general >= 2 && !$lettersLeft);

            // 1) Colchetes sempre marcam
            $line = preg_replace_callback($RE_BRACKET, fn($m)=>'<span class="chord">'.$m[1].'</span>', $escaped);

            if ($isChordLine) {
                // Em linha de acordes, marca tudo (inclusive Em)
                $line = preg_replace_callback($RE_GEN, fn($m)=>$m[1].'<span class="chord">'.$m[2].'</span>', $line);
            } else {
                // Em linha de letra, só marca acordes fortes (sem 'm' simples)
                $line = preg_replace_callback($RE_STR, fn($m)=>$m[1].'<span class="chord">'.$m[2].'</span>', $line);
            }

            $out[] = $line;
        }
        return implode("\n", $out);
    }
      }
      if (!function_exists('keyFromLetra')) {
          function keyFromLetra($letra) {
              return preg_match('/^\s*(?:\[)?([A-Ga-g][b#]?)/m', (string)$letra, $mm) ? $mm[1] : null;
          }
      }
      if (!function_exists('splitEstrofes')) {
          function splitEstrofes($texto) {
              // Divide por uma ou mais linhas em branco
              $parts = preg_split("/\R{2,}/u", $texto);
              return array_map('rtrim', $parts);
          }
      }
      if (!function_exists('transposeChordFull')) {
          function transposeChordFull($chord, $semitones){
              $sharp = ['C','C#','D','D#','E','F','F#','G','G#','A','A#','B'];
              $flat  = ['C','Db','D','Eb','E','F','Gb','G','Ab','A','Bb','B'];
              $eff   = (($semitones%12)+12)%12;

              // raiz + resto (aceita minúsculas) ex.: g#m7/B
              if (!preg_match('/^([A-Ga-g])([b#]?)(.*)$/u', (string)$chord, $m)) return $chord;
              $letter = strtoupper($m[1]);
              $acc    = $m[2] ?? '';
              $suffix = $m[3] ?? '';
              $root   = $letter.$acc; // ex.: B, Bb, F#

              // transpõe a raiz preservando #/b do input quando possível
              $idx = array_search($root, $sharp, true); $useSharp = true;
              if ($idx === false){ $idx = array_search($root, $flat, true); $useSharp = false; }
              if ($idx !== false){
                  $arr  = $useSharp ? $sharp : $flat;
                  $root = $arr[($idx+$eff)%12];
              }

              // transpõe baixo /X dentro do sufixo (aceita minúsculas)
              $suffix = preg_replace_callback('/\/([A-Ga-g])([b#]?)/u', function($mm) use($sharp,$flat,$eff){
                  $letter = strtoupper($mm[1]);
                  $acc    = $mm[2] ?? '';
                  $b      = $letter.$acc;
                  $bi = array_search($b, $sharp, true); $useS = true;
                  if ($bi === false){ $bi = array_search($b, $flat, true); $useS = false; }
                  if ($bi === false) return '/'.$mm[1].$acc;
                  $arr = $useS ? $sharp : $flat;
                  return '/'.$arr[($bi+$eff)%12];
              }, $suffix);

              return $root.$suffix;
          }
      }
      if (!function_exists('applyTransposeToHtml')) {
          function applyTransposeToHtml($html, $semitones){
              $eff = (($semitones%12)+12)%12;
              if ($eff === 0) return $html;
              return preg_replace_callback('/<span class="chord">([^<]+)<\/span>/u', function($m) use($eff){
                  return '<span class="chord">'.transposeChordFull($m[1], $eff).'</span>';
              }, $html);
          }
      }
    @endphp

    @php $prefsById = $prefsById ?? []; @endphp

    @forelse ($cantos as $canto)
      @php
        $pref = $prefsById[$canto->id] ?? null;
        $off  = (int)($pref['offset'] ?? 0);
        $cap  = (int)($pref['capo']   ?? 0);

        $tomBase  = keyFromLetra($canto->letra);
        $tomFinal = $tomBase ? transposeChordFull($tomBase, $off) : null;

        $tipo = strtoupper($canto->tipos->pluck('nome')->join(', ') ?? '');
        $tit  = strtoupper($canto->titulo ?? '');

        $metaParts = [];
        if($tomFinal) $metaParts[] = "Tom: {$tomFinal}";
        if($cap)      $metaParts[] = "Capo: {$cap}";
        if($canto->ritmo) $metaParts[] = "Ritmo: {$canto->ritmo}";
        $meta = implode(' | ', $metaParts);

        $estrofes = splitEstrofes($canto->letra);
      @endphp

      <div class="canto">
        <div class="titulo">
          {{ sprintf('%02d', $loop->iteration) }} - {{ $tipo }} – {{ $tit }}@if($meta) ({{ $meta }})@endif
        </div>

        <div class="letra">
          @foreach ($estrofes as $stanza)
            @php
              $html = formatChordSpans($stanza);
              $html = applyTransposeToHtml($html, $off);
            @endphp
            <pre class="estrofe">{!! $html !!}</pre>
          @endforeach
        </div>
      </div>
    @empty
      <p style="text-align:center;color:#64748b;">Nenhum canto selecionado.</p>
    @endforelse
  </div>

  <div class="footer">Página <span class="pagenum"></span></div>
</body>
</html>
