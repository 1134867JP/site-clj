<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CantosController;
use App\Http\Controllers\Settings\CantoTipoController;
use App\Http\Controllers\FeedbackController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $qtCantos     = \App\Models\Canto::count();
    $qtCategorias = \App\Models\CantoTipo::count();
    $qtComTom     = \App\Models\Canto::whereNotNull('tom')->where('tom', '<>', '')->count();
    $recentCantos = \App\Models\Canto::with('tipos')->orderByDesc('created_at')->limit(5)->get();

    return view('dashboard', compact('qtCantos','qtCategorias','qtComTom','recentCantos'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rota para exibir o perfil do usuário
    Route::get('/profile/show', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');

    // 👇 Rotas dos Cantos
    Route::get('cantos/pdf', [CantosController::class, 'gerarPDF'])->name('cantos.pdf');
    Route::get('cantos/selecionar', [CantosController::class, 'selecionar'])->name('cantos.selecionar');
    Route::resource('cantos', CantosController::class);

    Route::post('/cantos/{canto}/prefs', function(\App\Models\Canto $canto) {
        request()->validate(['offset'=>'integer|min:0|max:11','capo'=>'integer|min:0|max:12']);
        \App\Models\CantoUserPref::updateOrCreate(
            ['user_id'=>auth()->id(), 'canto_id'=>$canto->id],
            ['offset'=>request('offset',0), 'capo'=>request('capo',0)]
        );
        return response()->noContent();
    })->middleware('auth');

    // Configurações (somente admin via policy CantoTipo@create)
    Route::middleware('can:create,App\\Models\\CantoTipo')->group(function () {
        Route::get('/settings/general', [\App\Http\Controllers\Settings\GeneralSettingsController::class, 'index'])->name('settings.general');

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::post('/tipos', [CantoTipoController::class, 'store'])->name('tipos.store');
            Route::patch('/tipos/{tipo}', [CantoTipoController::class, 'update'])->name('tipos.update');
            Route::delete('/tipos/{tipo}', [CantoTipoController::class, 'destroy'])->name('tipos.destroy');
        });
    });

    // Admin-only: Feedbacks (via policy)
    Route::middleware('can:viewAny,App\\Models\\Feedback')->prefix('settings')->name('settings.')->group(function () {
        Route::get('/feedback', [\App\Http\Controllers\Settings\FeedbackAdminController::class, 'index'])->name('feedback.index');
    });
});

// Endpoint público para feedback (não exige login)
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');

require __DIR__.'/auth.php';
