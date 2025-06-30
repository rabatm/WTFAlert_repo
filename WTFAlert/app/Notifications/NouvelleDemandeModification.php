<?php

namespace App\Notifications;

use App\Models\DemandeModification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleDemandeModification extends Notification
{
    use Queueable;

    /**
     * La demande de modification
     *
     * @var \App\Models\DemandeModification
     */
    public $demande;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\DemandeModification  $demande
     * @return void
     */
    public function __construct(DemandeModification $demande)
    {
        $this->demande = $demande;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $types = [
            'ajout_habitant' => 'Ajout d\'un habitant',
            'suppression_habitant' => 'Suppression d\'un habitant',
            'demande_info' => 'Demande d\'information'
        ];

        $url = url('/admin/demandes-modification');

        return (new MailMessage)
            ->subject('Nouvelle demande de modification #' . $this->demande->id)
            ->greeting('Bonjour Admin,')
            ->line('Une nouvelle demande de modification a été soumise.')
            ->line('**Type de demande:** ' . ($types[$this->demande->type] ?? $this->demande->type))
            ->line('**Foyer concerné:** #' . $this->demande->foyer_id)
            ->line('**Message:** ' . $this->demande->message)
            ->action('Voir la demande', $url)
            ->line('Merci d\'utiliser notre application !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'demande_id' => $this->demande->id,
            'type' => $this->demande->type,
            'foyer_id' => $this->demande->foyer_id,
            'message' => 'Nouvelle demande de modification #' . $this->demande->id,
            'url' => '/admin/demandes-modification',
        ];
    }
}
