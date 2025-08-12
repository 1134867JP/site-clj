<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Canto;
use App\Models\CantoTipo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CantosController extends Controller
{
    use AuthorizesRequests;

    /** Lista cantos (filtro por tipo e busca por q) */
    public function index(Request $request)
    {
        $tipos = CantoTipo::orderBy('ord')->orderBy('nome')->get();

        $query = Canto::query()->with('tipos');

        // filtro por um ou mais tipos (id ou nome)
        if ($request->filled('tipo')) {
            $tipoParam = $request->input('tipo');
            $tiposFiltro = is_array($tipoParam) ? $tipoParam : explode(',', (string)$tipoParam);
            $tiposFiltro = array_filter(array_map('trim', $tiposFiltro));

            if ($tiposFiltro) {
                $query->whereHas('tipos', function ($q) use ($tiposFiltro) {
                    $q->whereIn('canto_tipos.id', array_filter($tiposFiltro, 'ctype_digit'))
                      ->orWhereIn('canto_tipos.nome', $tiposFiltro);
                });
            }
        }

        // busca por título
        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->where('titulo', 'like', "%{$q}%");
        }

        $cantos = $query->get();

        return view('cantos.index', compact('cantos', 'tipos'));
    }

    public function create()
    {
        $this->authorize('create', \App\Models\Canto::class);
        $tipos = CantoTipo::orderBy('ord')->orderBy('nome')->get();

        return view('cantos.create', compact('tipos'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\Canto::class);

        $validated = $request->validate([
            'titulo'        => 'required|string|max:255',
            'letra'         => 'required|string',
            'tipos'         => ['required','array','min:1'],
            'tipos.*'       => ['integer','exists:canto_tipos,id'],
        ]);

        $canto = Canto::create([
            'titulo' => $validated['titulo'],
            'letra'  => $validated['letra'],
            'notas'  => $request->input('notas'),
            'tom'    => $request->input('tom'),
        ]);

        $canto->tipos()->sync($validated['tipos']);

        return redirect()->route('cantos.index')->with('success', 'Canto criado com sucesso!');
    }

    public function show($id)
    {
        $canto = Canto::with('tipos')->findOrFail($id);
        $key   = self::getKeyFromLetra($canto->letra);

        return view('cantos.show', compact('canto', 'key'));
    }

    public function edit($id)
    {
        $canto = Canto::with('tipos')->findOrFail($id);
        $this->authorize('update', $canto);

        $tipos = CantoTipo::orderBy('ord')->orderBy('nome')->get();

        return view('cantos.edit', compact('canto', 'tipos'));
    }

    public function update(Request $request, $id)
    {
        $canto = Canto::findOrFail($id);
        $this->authorize('update', $canto);

        $validated = $request->validate([
            'titulo'  => 'required|string|max:255',
            'letra'   => 'required|string',
            'tipos'   => ['required','array','min:1'],
            'tipos.*' => ['integer','exists:canto_tipos,id'],
        ]);

        $canto->update([
            'titulo' => $validated['titulo'],
            'letra'  => $validated['letra'],
            'notas'  => $request->input('notas'),
            'tom'    => $request->input('tom'),
        ]);

        $canto->tipos()->sync($validated['tipos']);

        return redirect()->route('cantos.index')->with('success', 'Canto atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $canto = Canto::findOrFail($id);
        $this->authorize('delete', $canto);

        $canto->delete();

        return redirect()->route('cantos.index')->with('success', 'Canto removido com sucesso!');
    }

    /** Tela de seleção para gerar PDF */
    public function selecionar(Request $request)
    {
        $tipos = CantoTipo::orderBy('ord')->orderBy('nome')->get();

        $query = Canto::query()->with('tipos');

        if ($request->filled('tipo')) {
            $tipoParam = $request->input('tipo');
            $tiposFiltro = is_array($tipoParam) ? $tipoParam : explode(',', (string)$tipoParam);
            $tiposFiltro = array_filter(array_map('trim', $tiposFiltro));
            if ($tiposFiltro) {
                $query->whereHas('tipos', function ($q) use ($tiposFiltro) {
                    $q->whereIn('canto_tipos.id', array_filter($tiposFiltro, 'ctype_digit'))
                      ->orWhereIn('canto_tipos.nome', $tiposFiltro);
                });
            }
        }

        if ($request->filled('q')) {
            $q = trim((string) $request->input('q'));
            $query->where('titulo', 'like', "%{$q}%");
        }

        $cantos = $query->get();

        return view('cantos.selecionar', compact('cantos', 'tipos'));
    }

    /** Gera o PDF com transposição/capo vindos da tela (GET prefs=base64url) */
    public function gerarPDF(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        $ids = array_map('intval', $ids);
        $ids = array_values(array_filter($ids));

        $cantos = Canto::with('tipos')->whereIn('id', $ids)->get();

        if ($cantos->isEmpty()) {
            return redirect()->route('cantos.index')->with('error', 'Nenhum canto selecionado.');
        }

        // Lê prefs seguras (offset 0..11 / capo 0..12) vindas via base64url
        $prefsById = $this->readPrefsFromBase64Url((string) $request->input('prefs', ''));

        // Layout: 1 ou 2 colunas (padrão 2 colunas, compacto)
        $cols = (int) $request->input('cols', 2);
        $cols = max(1, min(2, $cols));

        $pdf = Pdf::loadView('cantos.pdf', [
            'cantos'    => $cantos,
            'prefsById' => $prefsById,
            'cols'      => $cols,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('cantos_missa_clj.pdf');
    }

    /* -------------------------- Helpers -------------------------- */

    /** Extrai o tom da letra (primeiro acorde no início de linha) */
    public static function getKeyFromLetra($letra)
    {
        if (preg_match('/^\s*\[?([A-G][b#]?)/m', (string) $letra, $m)) {
            return $m[1];
        }
        return 'C';
    }

    /** Marca acordes com <span class="chord">…</span> (mantendo HTML escapado) */
    public static function formatCifra($texto)
    {
        $pattern = '/(^|[\s(])' .
            '([A-G](?:#|b)?' .
            '(?:maj7|maj9|maj11|maj13|m7|m9|m11|m13|maj|min|m|dim|aug|sus2|sus4|add9|add11|add13|6|7|9|11|13)?' .
            '(?:\([^\)]*\))?' .
            '(?:\/[A-G](?:#|b)?(?:m7|m|7|9|11|13)?)?' .
            ')' .
            '(?=$|\s|[),.;:])(?![a-zà-úâêîôûãõç])' .
            '/mu';

        $escaped = e((string) $texto);
        return preg_replace_callback($pattern, function ($m) {
            return $m[1] . '<span class="chord">' . $m[2] . '</span>';
        }, $escaped);
    }

    /** Decodifica prefs base64url e normaliza: [id => ['offset'=>0..11, 'capo'=>0..12]] */
    protected function readPrefsFromBase64Url(string $payload): array
    {
        if ($payload === '') return [];

        // normaliza base64url -> base64
        $b64 = strtr($payload, ' ', '+');         // caso GET substitua espaço por +
        $b64 = strtr($b64, '-_', '+/');           // url-safe -> std
        $pad = strlen($b64) % 4;
        if ($pad) $b64 .= str_repeat('=', 4 - $pad);

        $arr = json_decode(base64_decode($b64), true);
        if (!is_array($arr)) return [];

        $out = [];
        foreach ($arr as $p) {
            $id = (int)($p['id'] ?? 0);
            if (!$id) continue;
            $off = (int)($p['offset'] ?? 0);
            $cap = (int)($p['capo']   ?? 0);

            // normaliza faixas
            $off = (($off % 12) + 12) % 12;        // 0..11
            $cap = max(0, min(12, $cap));          // 0..12

            $out[$id] = ['offset' => $off, 'capo' => $cap];
        }

        return $out;
    }
}
