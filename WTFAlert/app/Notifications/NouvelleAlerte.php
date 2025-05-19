<?php

namespace App\Notifications;

use App\Models\Alerte;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleAlerte extends Notification implements ShouldQueue
{
    use Queueable;

    protected $alerte;

    public function __construct(Alerte $alerte)
    {
        $this->alerte = $alerte;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
        {
            $typeLabel = [
                'info' => 'Information',
                'warning' => 'Avertissement',
                'alert' => 'Alerte urgente'
            ][$this->alerte->type];

            $urgenceStyle = [
                'info' => 'color: blue;',
                'warning' => 'color: orange;',
                'alert' => 'color: red; font-weight: bold;'
            ][$this->alerte->type];

            $mail = (new MailMessage)
                ->subject("[$typeLabel] Nouvelle alerte : {$this->alerte->titre}")
                ->greeting('Bonjour,')
                ->line("Une nouvelle alerte a été signalée dans votre secteur.")
                ->line("<div style='$urgenceStyle'>Type: $typeLabel</div>")
                ->line("**Titre**: {$this->alerte->titre}")
                ->line("**Description**: {$this->alerte->description}")
                ->line("**Localisation**: {$this->alerte->localisation}");
            
            // Récupérer et ajouter les photos si elles existent
            $photos = $this->alerte->photos;
            if ($photos && $photos->count() > 0) {
                $mail->line("**Photos de l'alerte**:");
                
                foreach ($photos as $photo) {
                    // Utiliser le chemin de la photo depuis le modèle Photo
                    $imageUrl = url(Storage::url($photo->chemin));
                    $mail->line("<img src='$imageUrl' alt='{$photo->nom_original}' style='max-width: 100%; margin: 15px 0;'>");
                }
            }
            
            return $mail
                ->action('Voir l\'alerte', url("/alertes/{$this->alerte->id}"))
                ->line('Merci de votre attention.');
        }

    public function toArray($notifiable)
    {
        return [
            'alerte_id' => $this->alerte->id,
            'type' => $this->alerte->type,
            'titre' => $this->alerte->titre,
        ];
    }
}
