<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VulnerabilidadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
    Route::post('/vulnerabilidades/detectar-archivo', [VulnerabilidadController::class, 'detectarDesdeArchivo'])
        ->name('vulnerabilidades.detectar');



    Route::get('/notificaciones/marcar/{id}', [App\Http\Controllers\NotificacionController::class, 'marcarYRedirigir'])
        ->name('notificaciones.marcarYRedirigir');

    Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [VulnerabilidadController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/vulnerabilidades/simular', [VulnerabilidadController::class, 'simular'])->name('vulnerabilidades.simular');
    Route::resource('vulnerabilidades', VulnerabilidadController::class)->except(['show']);

    Route::get('/notificaciones', function () {
        return view('notificaciones.index', [
            'notificaciones' => auth()->user()->notifications()->paginate(10)
        ]);
    })->name('notificaciones.index');

    Route::post('/notificaciones/marcar-todas', [App\Http\Controllers\NotificacionController::class, 'marcarTodas'])
    ->middleware('auth')
    ->name('notificaciones.marcarTodas');

    Route::get('/vulnerabilidades/agrupadas', [VulnerabilidadController::class, 'agrupadasPorArchivo'])->name('vulnerabilidades.agrupadas');
    
    Route::post('/notificaciones/{id}/marcar-leida', [App\Http\Controllers\NotificacionController::class, 'marcarLeida'])->name('notificaciones.marcar');
});

require __DIR__.'/auth.php';

