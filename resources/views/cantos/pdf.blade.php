<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Cantos Missa CLJ</title>
  <style>
    /* Reserva espaço para o cabeçalho fixo */
    @page { margin: 96px 28px 36px 28px; } /* antes era 110px */
    * { box-sizing: border-box; }
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12.5px;
      line-height: 1.55;
      color: #111827;
    }

    /* ===== Cabeçalho fixo ===== */
    .header {
      position: fixed;
      top: -88px; left: 0; right: 0;  /* acompanha o margin-top */
      height: 80px;
      z-index: 10;
    }
    .header-table { width: 100%; border-collapse: collapse; }
    .header-table td { vertical-align: middle; }
    .h-left { width: 90px; }
    .h-right { width: 90px; text-align: right; }
    .brand {
      font-weight: 800;
      text-transform: uppercase;
      text-align: center;
      font-size: 16px;
      color: #0f172a;
      letter-spacing: .4px;
      white-space: nowrap;
    }
    .logo { max-height: 36px; width: auto; display: inline-block; }
    .hr { border: 0; border-top: 1px solid #cbd5e1; margin: 6px 0 0; }

    /* ===== Conteúdo ===== */
    .canto    { page-break-inside: avoid; margin-bottom: 20px; }
    .titulo   {
      font-weight: 700; font-size: 13.5px; text-transform: uppercase; color: #0f172a;
      border-bottom: 1px solid #cbd5e1; padding-bottom: 4px; margin-bottom: 8px;
    }

    /* Cada estrofe vira um bloco "não-quebrável" */
    .letra    { border: 1px solid #e2e8f0; background: #f1f5f9; border-radius: 6px; padding: 8px 10px; }
    .estrofe  {
      white-space: pre-wrap;            /* preserva \n */
      font-family: DejaVu Sans Mono, DejaVu Sans, monospace;
      font-size: 12.5px; line-height: 1.55;
      margin: 6px 0;                    /* espaço entre estrofes */
      page-break-inside: avoid;         /* <- não quebra aqui */
    }
    .chord { color: #d97706; font-weight: 700; }

    /* ===== Rodapé ===== */
    .footer {
      position: fixed; bottom: -14px; left: 0; right: 0;
      color: #64748b; font-size: 11px; text-align: right;
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
            $logoLeft = str_replace('\\','/', public_path('storage/images/clj_logo_left.png'));
          @endphp
          @if (file_exists($logoLeft))
            <img class="logo" src="{{ $logoLeft }}" alt="CLJ">
          @endif
        </td>
        <td class="brand">CANTOS MISSA CLJ</td>
        <td class="h-right">
          @php
            $logoRight = str_replace('\\','/', public_path('storage/images/clj_logo_right.png'));
          @endphp
          @if (file_exists($logoRight))
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
              $escaped = e($texto);
              $pattern = '/(^|[\s(])' .
                         '(\[[A-G](?:#|b)?\]|[A-G](?:#|b)?' .
                           '(?:maj7|maj9|maj11|maj13|m7|m9|m11|m13|maj|min|m|dim|aug|sus2|sus4|add9|add11|add13|6|7|9|11|13)?' .
                           '(?:\([^\)]*\))?' .
                           '(?:\/[A-G](?:#|b)?(?:m|7|9|11|13)?)?' .
                         ')' .
                         '(?=$|\s|[),.;:])(?![a-zà-úâêîôûãõç])/mu';
              return preg_replace_callback($pattern, fn($m) => $m[1].'<span class="chord">'.$m[2].'</span>', $escaped);
          }
      }
      if (!function_exists('keyFromLetra')) {
          function keyFromLetra($letra) {
              return preg_match('/^\s*(?:\[)?([A-G][b#]?)/m', $letra, $mm) ? $mm[1] : null;
          }
      }
      if (!function_exists('splitEstrofes')) {
          function splitEstrofes($texto) {
              // Divide por uma ou mais linhas em branco
              $parts = preg_split("/\R{2,}/u", $texto); // \R = qualquer quebra
              return array_map('rtrim', $parts);
          }
      }
    @endphp

    @php $i = 1; @endphp
    @forelse ($cantos as $canto)
      @php
        $num   = str_pad($i, 2, '0', STR_PAD_LEFT);
        $tipo  = strtoupper($canto->tipo ?? '');
        $tit   = strtoupper($canto->titulo ?? '');
        $tom   = keyFromLetra($canto->letra);
        $ritmo = $canto->ritmo ?? null;
        $meta  = trim(($tom ? "Tom: {$tom}" : '').($tom && $ritmo ? ' | ' : '').($ritmo ? "Ritmo: {$ritmo}" : ''));
        $estrofes = splitEstrofes($canto->letra);
      @endphp

      <div class="canto">
        <div class="titulo">
          {{ $num }} - {{ $tipo }} – {{ $tit }}@if($meta) ({{ $meta }})@endif
        </div>

        <div class="letra">
          @php $letra = $canto->letra_pdf ?? null; @endphp
          @foreach ($estrofes as $idx => $stanza)
            <pre class="estrofe">{!! $letra ? $letra : formatChordSpans($stanza) !!}</pre>
          @endforeach
        </div>
      </div>

      @php $i++; @endphp
    @empty
      <p style="text-align:center;color:#64748b;">Nenhum canto selecionado.</p>
    @endforelse
  </div>

  <div class="footer">Página <span class="pagenum"></span></div>
</body>
</html>
