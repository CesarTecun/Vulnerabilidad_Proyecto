<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VulnerabilidadController;
use App\Http\Controllers\PatronVulnerabilidadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Fuera del grupo auth porque es usado antes de autenticarse
Route::post('/vulnerabilidades/detectar-archivo', [VulnerabilidadController::class, 'detectarDesdeArchivo'])
    ->name('vulnerabilidades.detectar');

// Ruta especial sin auth
Route::get('/notificaciones/marcar/{id}', [App\Http\Controllers\NotificacionController::class, 'marcarYRedirigir'])
    ->name('notificaciones.marcarYRedirigir');

Route::middleware(['auth'])->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [VulnerabilidadController::class, 'dashboard'])->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vulnerabilidades
    Route::post('/vulnerabilidades/simular', [VulnerabilidadController::class, 'simular'])->name('vulnerabilidades.simular');
    Route::resource('vulnerabilidades', VulnerabilidadController::class)->except(['show']);

    // Patrones (🔒 ahora protegido con auth)
    Route::resource('patrones', PatronVulnerabilidadController::class);

    // Notificaciones
    Route::get('/notificaciones', function () {
        return view('notificaciones.index', [
            'notificaciones' => auth()->user()->notifications()->paginate(10)
        ]);
    })->name('notificaciones.index');

    Route::post('/notificaciones/marcar-todas', [App\Http\Controllers\NotificacionController::class, 'marcarTodas'])
        ->name('notificaciones.marcarTodas');

    Route::post('/notificaciones/{id}/marcar-leida', [App\Http\Controllers\NotificacionController::class, 'marcarLeida'])
        ->name('notificaciones.marcar');
});

require __DIR__.'/auth.php';
