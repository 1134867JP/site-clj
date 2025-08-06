<x-app-layout>
    <div class="max-w-6xl mx-auto py-8">
        <div class="bg-white shadow rounded-lg p-8 space-y-4">
            <div class="flex flex-col md:flex-row gap-8 mb-8">
                <div class="flex flex-col items-center md:items-start w-full md:w-1/3">
                    <div class="flex flex-col gap-2 w-full mb-6">
                        <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                            <a href="{{ route('cantos.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full font-semibold shadow hover:bg-gray-200 border border-gray-300 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                Voltar
                            </a>
                            <a href="{{ route('cantos.edit', $canto) }}" class="px-4 py-2 bg-yellow-400 text-white rounded-full font-semibold shadow hover:bg-yellow-500 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6 6M3 21h6l11-11a2.828 2.828 0 00-4-4L5 17v4z" /></svg>
                                Editar
                            </a>
                        </div>
                    </div>
                    <div class="text-2xl font-bold text-gray-900 text-center md:text-left mt-2">{{ $canto->titulo }}</div>
                    <div class="flex flex-col gap-2 w-full mt-4">
                        <button class="px-4 py-2 bg-gray-100 rounded text-gray-700 font-semibold text-sm hover:bg-gray-200">Simplificar cifra</button>
                        <button class="px-4 py-2 bg-gray-100 rounded text-gray-700 font-semibold text-sm hover:bg-gray-200">Auto rolagem</button>
                        <div class="flex items-center gap-2">
                            <button onclick="changeFontSize(-1)" class="px-2 py-1 bg-gray-200 rounded text-lg">A-</button>
                            <span class="text-sm">Texto</span>
                            <button onclick="changeFontSize(1)" class="px-2 py-1 bg-gray-200 rounded text-lg">A+</button>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="transposeCifra(-1)" class="px-2 py-1 bg-gray-200 rounded text-lg">-½ Tom</button>
                            <span class="text-sm">Tom</span>
                            <button onclick="transposeCifra(1)" class="px-2 py-1 bg-gray-200 rounded text-lg">+½ Tom</button>
                        </div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="font-semibold text-lg">Tom:</span>
                        <span id="current-key" class="font-bold text-orange-600">{{ getKeyFromLetra($canto->letra) }}</span>
                    </div>
                    <pre id="cifra-letra" class="bg-white p-4 rounded-lg text-gray-900 whitespace-pre-wrap font-mono text-base leading-relaxed border border-gray-200 shadow-inner">{!! formatCifra($canto->letra) !!}</pre>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('cantos.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 font-semibold">Voltar</a>
                <a href="{{ route('cantos.edit', $canto) }}" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500 font-semibold">Editar</a>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
const notesSharp = ['C','C#','D','D#','E','F','F#','G','G#','A','A#','B'];
const notesFlat  = ['C','Db','D','Eb','E','F','Gb','G','Ab','A','Bb','B'];
let cifraOriginal = {!! json_encode($canto->letra) !!};
let fontSize = 16;

function transposeChord(chord, semitones) {
    let regex = /^([A-G][b#]?)(.*)$/;
    let match = chord.match(regex);
    if (!match) return chord;
    let root = match[1];
    let suffix = match[2] || '';
    let idx = notesSharp.indexOf(root);
    let useSharp = true;
    if (idx === -1) {
        idx = notesFlat.indexOf(root);
        useSharp = false;
    }
    if (idx === -1) return chord;
    let newIdx = (idx + semitones + 12) % 12;
    let newRoot = useSharp ? notesSharp[newIdx] : notesFlat[newIdx];
    return newRoot + suffix;
}

function transposeLine(line, semitones) {
    return line.replace(/<span class="chord">([^<]+)<\/span>/g, function(_, chord) {
        return `<span class="chord">${transposeChord(chord, semitones)}</span>`;
    });
}

function transposeText(text, semitones) {
    return text.split('\n').map(line => transposeLine(line, semitones)).join('\n');
}

function transposeCifra(semitones) {
    let cifra = document.getElementById('cifra-letra').innerHTML;
    document.getElementById('cifra-letra').innerHTML = transposeText(cifra, semitones);
    updateKey(semitones);
}

function changeFontSize(delta) {
    fontSize += delta;
    document.getElementById('cifra-letra').style.fontSize = fontSize + 'px';
}

function updateKey(semitones) {
    let key = document.getElementById('current-key').textContent;
    let idx = notesSharp.indexOf(key);
    if (idx === -1) idx = notesFlat.indexOf(key);
    if (idx !== -1) {
        let newIdx = (idx + semitones + 12) % 12;
        document.getElementById('current-key').textContent = notesSharp[newIdx];
    }
}
</script>

<style>
#cifra-letra { color: #1f2937; font-family: monospace; }
.chord { color: #e67e22; font-weight: bold; }
</style>

@php
function getKeyFromLetra($letra) {
    if (preg_match('/^\s*\[?([A-G][b#]?)/m', $letra, $matches)) {
        return $matches[1];
    }
    return 'C';
}

function formatCifra($texto) {
    return preg_replace_callback(
        '/(?<=^|\s)([A-G][b#]?m?(?:7|9|11|13)?(?:sus|add|dim|aug)?\d*\+?\/?[A-G]?[b#]?m?(?:7|9|11|13)?\d*)/m',
        fn($m) => '<span class="chord">' . e($m[1]) . '</span>',
        e($texto)
    );
}
@endphp
