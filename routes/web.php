<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CantosController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
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
});

require __DIR__.'/auth.php';
