<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\CantoTipo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GeneralSettingsController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('create', \App\Models\CantoTipo::class); // admin-only gate
        $tipos = CantoTipo::orderBy('ord')->orderBy('nome')->get();
        return view('settings.general', compact('tipos'));
    }
}
