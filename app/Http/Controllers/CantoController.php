<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Canto;
use Barryvdh\DomPDF\Facade\Pdf;

class CantoController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->get('tipo');
        $query = Canto::query();

        if ($tipo) {
            $query->where('tipo', $tipo);
        }

        $tipos = ['Entrada', 'Ato Penitencial', 'Glória', 'Ofertório', 'Santo', 'Cordeiro', 'Comunhão', 'Final', 'Abraço da Paz', 'Pai Nosso'];

        return view('cantos.index', [
            'tipos' => $tipos,
            'cantos' => $query->get(),
        ]);
    }

    public function gerarPDF(Request $request)
    {
        $cantos = Canto::whereIn('id', $request->get('ids', []))->get();
        $pdf = Pdf::loadView('cantos.pdf', compact('cantos'));
        return $pdf->download('missa.pdf');
    }
}
