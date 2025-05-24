<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NuevaVulnerabilidadDetectada extends Notification
{
    use Queueable;

    protected string $nombre;
    protected int $vulnerabilidadId;
    protected string $prioridad;

    /**
     * Crear una nueva instancia de notificación.
     */
    public function __construct(string $nombre, int $vulnerabilidadId, string $prioridad = 'media')
    {
        $this->nombre = $nombre;
        $this->vulnerabilidadId = $vulnerabilidadId;
        $this->prioridad = $prioridad; // 'alta', 'media', 'baja'
    }

    /**
     * Canales por los que se enviará la notificación.
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Puedes añadir 'mail' si deseas
    }

    /**
     * Opcional: para envío por correo (si lo activas)
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nueva vulnerabilidad detectada')
            ->line("Se ha detectado una nueva vulnerabilidad: {$this->nombre}.")
            ->action('Ver informe', route('vulnerabilidades.show', $this->vulnerabilidadId))
            ->line('Por favor revisa los detalles.');
    }

    /**
     * Datos que se guardarán en la base de datos.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'mensaje' => "🔔 Nueva vulnerabilidad detectada: {$this->nombre}.",
            'url' => route('vulnerabilidades.show', $this->vulnerabilidadId),
            'tipo' => 'vulnerabilidad',
            'nombre' => $this->nombre,
            'prioridad' => $this->prioridad
        ];
    }
}
