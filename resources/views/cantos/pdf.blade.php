<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cantos da Missa</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            line-height: 1.6;
        }
        h1 {
            text-align: center;
            color: #1e40af;
            margin-bottom: 24px;
        }
        h2 {
            margin-top: 32px;
            font-size: 16px;
            color: #1e3a8a;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }
        .canto {
            margin-bottom: 32px;
        }
        .titulo {
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .letra {
            background: #f1f5f9;
            padding: 10px;
            border-radius: 6px;
            font-family: monospace;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>

    <h1>CANTOS DA MISSA</h1>

    @php $i = 1; @endphp

    @forelse ($cantos as $canto)
        <div class="canto">
            <div class="titulo">
                {{ str_pad($i, 2, '0', STR_PAD_LEFT) }} - {{ strtoupper($canto->tipo) }} – {{ $canto->titulo }}
            </div>
            <div class="letra">{{ $canto->letra }}</div>
        </div>
        @php $i++; @endphp
    @empty
        <p style="text-align: center; color: #888;">Nenhum canto selecionado.</p>
    @endforelse

</body>
</html>
