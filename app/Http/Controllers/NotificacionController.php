<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Mostrar todas las notificaciones del usuario.
     */
    public function index()
    {
        $notificaciones = Auth::user()->notifications()->paginate(10);
        return view('notificaciones.index', compact('notificaciones'));
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