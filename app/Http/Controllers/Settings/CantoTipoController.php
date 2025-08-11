<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\CantoTipo;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CantoTipoController extends Controller
{
    use AuthorizesRequests; 

    public function store(Request $request)
    {
        $this->authorize('create', CantoTipo::class);
        
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:canto_tipos,nome'],
            'ord' => ['nullable', 'integer', 'min:0'],
        ]);

        CantoTipo::create([
            'nome' => $validated['nome'],
            'ord' => $validated['ord'] ?? 0,
        ]);

        return back()->with('status', 'Tipo criado com sucesso.');
    }

    public function update(Request $request, CantoTipo $tipo)
    {
        $this->authorize('update', $tipo);

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:canto_tipos,nome,' . $tipo->id],
            'ord' => ['nullable', 'integer', 'min:0'],
        ]);

        $tipo->update($validated);

        return back()->with('status', 'Tipo atualizado com sucesso.');
    }

    public function destroy(CantoTipo $tipo)
    {
        $this->authorize('delete', $tipo);
        
        $tipo->delete();
        return back()->with('status', 'Tipo removido com sucesso.');
    }
}
