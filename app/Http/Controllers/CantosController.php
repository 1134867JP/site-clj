<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Canto;
use Barryvdh\DomPDF\Facade\Pdf;

class CantosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cantos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tipos = ['Entrada', 'Ato Penitencial', 'Glória', 'Ofertório', 'Santo', 'Cordeiro', 'Comunhão', 'Final', 'Abraço da Paz', 'Pai Nosso'];
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo' => ['required', 'string', 'in:' . implode(',', $tipos)],
            'letra' => 'required|string',
        ]);
        Canto::create($validated);
        return redirect()->route('cantos.index')->with('success', 'Canto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $canto = Canto::findOrFail($id);
        return view('cantos.show', compact('canto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $canto = Canto::findOrFail($id);
        return view('cantos.edit', compact('canto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tipos = ['Entrada', 'Ato Penitencial', 'Glória', 'Ofertório', 'Santo', 'Cordeiro', 'Comunhão', 'Final', 'Abraço da Paz', 'Pai Nosso'];
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo' => ['required', 'string', 'in:' . implode(',', $tipos)],
            'letra' => 'required|string',
        ]);
        $canto = Canto::findOrFail($id);
        $canto->update($validated);
        return redirect()->route('cantos.index')->with('success', 'Canto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $canto = Canto::findOrFail($id);
        $canto->delete();
        return redirect()->route('cantos.index')->with('success', 'Canto removido com sucesso!');
    }

    /**
     * Gera o PDF dos cantos selecionados.
     */
    public function gerarPDF(Request $request)
    {
        $ids = $request->get('ids', []);
        if (empty($ids)) {
            return redirect()->route('cantos.index')->with('error', 'Nenhum canto foi selecionado.');
        }

        $cantos = Canto::whereIn('id', $ids)->get();

        if ($cantos->isEmpty()) {
            return redirect()->route('cantos.index')->with('error', 'IDs inválidos.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cantos.pdf', compact('cantos'));
        return $pdf->download('missa.pdf'); // <- encerra aqui
    }

    /**
     * Exibe a view de seleção de cantos.
     */
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
}