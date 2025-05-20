<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VulnerabilidadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



    // ✅ Acceso para cualquier usuario autenticado (perfil, dashboard público)
    Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [VulnerabilidadController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/vulnerabilidades/simular', [VulnerabilidadController::class, 'simular'])->name('vulnerabilidades.simular');
    Route::resource('vulnerabilidades', VulnerabilidadController::class)->except(['show']);
});

require __DIR__.'/auth.php';
