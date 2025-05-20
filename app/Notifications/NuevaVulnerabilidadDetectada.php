<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NuevaVulnerabilidadDetectada extends Notification
{
    use Queueable;

    protected $nombre;

    /**
     * Crea una nueva instancia de notificación.
     */
    public function __construct(string $nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * Canales por los que se entregará la notificación.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Puedes agregar 'mail' si deseas correo también
    }

    /**
     * Representación para correo (opcional).
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nueva vulnerabilidad detectada')
            ->line("Se ha detectado una nueva vulnerabilidad: {$this->nombre}.")
            ->action('Ver vulnerabilidades', url('/vulnerabilidades'))
            ->line('Por favor revisa los detalles y actúa según corresponda.');
    }

    /**
     * Representación para base de datos (notificación interna).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'mensaje' => "🔔 Nueva vulnerabilidad detectada: {$this->nombre}.",
            'url' => route('vulnerabilidades.index'),
            'tipo' => 'vulnerabilidad',
            'nombre' => $this->nombre
        ];
    }
}
