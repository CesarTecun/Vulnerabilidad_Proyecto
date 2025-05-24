<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Mostrar todas las notificaciones del usuario.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ordenar por prioridad: alta > media > baja, luego por fecha
        $notificaciones = $user->notifications()
            ->get()
            ->sortByDesc(function ($n) {
                return match($n->data['prioridad'] ?? 'media') {
                    'alta' => 3,
                    'media' => 2,
                    'baja' => 1,
                    default => 0
                };
            });

        return view('notificaciones.index', [
            'notificaciones' => $notificaciones
        ]);
    }


    /**
     * Marcar una notificación como leída.
     */
    public function marcarLeida($id)
    {
        $notificacion = Auth::user()->notifications()->findOrFail($id);
        $notificacion->markAsRead();

        return redirect()->back()->with('success', 'Notificación marcada como leída.');
    }

    /**
     * Marcar todas como leídas.
     */
    public function marcarTodas()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }

    public function marcarYRedirigir($id)
    {
        $notificacion = Auth::user()->notifications()->findOrFail($id);
    
        if (is_null($notificacion->read_at)) {
            $notificacion->markAsRead();
        }
    
        $url = $notificacion->data['url'] ?? route('dashboard');
    
        return redirect($url);
    }    
}