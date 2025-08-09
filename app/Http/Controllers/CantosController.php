<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Canto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CantosController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $tipos = ['Entrada', 'Ato Penitencial', 'Glória', 'Ofertório', 'Santo', 'Cordeiro', 'Comunhão', 'Final', 'Abraço da Paz', 'Pai Nosso'];

        $query = Canto::query();
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $cantos = $query->get();

        return view('cantos.index', compact('cantos', 'tipos'));
    }

    public function create()
    {
        $this->authorize('create', \App\Models\Canto::class);
        return view('cantos.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\Canto::class);
        $tipos = ['Entrada', 'Ato Penitencial', 'Glória', 'Ofertório', 'Santo', 'Cordeiro', 'Comunhão', 'Final', 'Abraço da Paz', 'Pai Nosso'];

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo'   => ['required', 'string', 'in:' . implode(',', $tipos)],
            'letra'  => 'required|string',
        ]);

        Canto::create($validated);

        return redirect()->route('cantos.index')->with('success', 'Canto criado com sucesso!');
    }

    public function show($id)
    {
        $canto = Canto::findOrFail($id);
        $key   = self::getKeyFromLetra($canto->letra); // calcula o tom e manda pra view

        return view('cantos.show', compact('canto', 'key'));
    }

    public function edit($id)
    {
        $canto = Canto::findOrFail($id);
        $this->authorize('update', $canto);
        return view('cantos.edit', compact('canto'));
    }

    public function update(Request $request, $id)
    {
        $canto = Canto::findOrFail($id);
        $this->authorize('update', $canto);
        $tipos = ['Entrada', 'Ato Penitencial', 'Glória', 'Ofertório', 'Santo', 'Cordeiro', 'Comunhão', 'Final', 'Abraço da Paz', 'Pai Nosso'];

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo'   => ['required', 'string', 'in:' . implode(',', $tipos)],
            'letra'  => 'required|string',
        ]);

        $canto->update($validated);

        return redirect()->route('cantos.index')->with('success', 'Canto atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $canto = Canto::findOrFail($id);
        $this->authorize('delete', $canto);
        $canto->delete();

        return redirect()->route('cantos.index')->with('success', 'Canto removido com sucesso!');
    }

    public function gerarPDF(Request $request)
    {
        $ids = $request->get('ids', []);
        $cantos = \App\Models\Canto::whereIn('id', $ids)->get();

        if ($cantos->isEmpty()) {
            return redirect()->route('cantos.index')->with('error', 'Nenhum canto selecionado.');
        }

        $pdf = Pdf::loadView('cantos.pdf', [
            'cantos' => $cantos,
            // se quiser passar algo a mais, pode
        ]);

        // melhora renderização
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('cantos_missa_clj.pdf');
    }


    public function selecionar(Request $request)
    {
        $tipos = ['Entrada', 'Ato Penitencial', 'Glória', 'Ofertório', 'Santo', 'Cordeiro', 'Comunhão', 'Final', 'Abraço da Paz', 'Pai Nosso'];

        $query = Canto::query();

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('q')) {
            $query->where('titulo', 'like', '%' . $request->q . '%');
        }

        $cantos = $query->get();

        return view('cantos.selecionar', compact('cantos', 'tipos'));
    }

    /** Extrai o tom da letra (primeiro acorde encontrado no início de linha) */
    public static function getKeyFromLetra($letra)
    {
        if (preg_match('/^\s*\[?([A-G][b#]?)/m', $letra, $m)) {
            return $m[1];
        }
        return 'C';
    }

    /**
     * Format lyrics by wrapping chords in a span with a specific class.
     */
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

        $escaped = e($texto); // Escapa HTML primeiro
        return preg_replace_callback($pattern, function ($m) {
            return $m[1] . '<span class="chord">' . $m[2] . '</span>';
        }, $escaped);
    }
}
