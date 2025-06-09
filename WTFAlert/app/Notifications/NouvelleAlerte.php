<?php

namespace App\Notifications;

use App\Models\Alerte;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; 

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

    protected function getExtensionFromMimeType(string $mimeType): string
    {
        $mimeMap = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
            // Ajoutez d'autres types MIME courants si nécessaire
        ];
        return $mimeMap[$mimeType] ?? 'dat'; // 'dat' comme extension par défaut si inconnue
    }

    public function getExtensionFromMimeTypeForCid(string $mimeType): string
    {
        $mimeMap = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/gif'  => 'gif',
        ];
        return $mimeMap[$mimeType] ?? 'dat';
    }

    public function toMail($notifiable)
    {
        $typeLabel = [
            'info' => 'Information',
            'alert' => 'Avertissement',
            'danger' => 'Alerte urgente',
            'accident' => 'Accident'
        ][$this->alerte->type];

        $urgenceStyle = [
            'info' => 'color: blue;',
            'alert' => 'color: orange;',
            'danger' => 'color: red; font-weight: bold;',
            'accident' => 'color: purple; font-weight: bold;'
        ][$this->alerte->type];
        // Traiter les photos pour les envoyer par email
        $photoData = [];
        // Si l'alerte a des photos
        if ($this->alerte->photos && $this->alerte->photos->count() > 0) {
                foreach ($this->alerte->photos as $photo) {
                    \Log::info('Traitement photo: ' . $photo->chemin);
                    
                    if (Storage::exists($photo->chemin)) {
                        \Log::info('Photo existe: ' . $photo->chemin);
                        // Récupérer le contenu binaire du fichier
                        $photoContent = Storage::get($photo->chemin);
                        
                        // Vérifier que le contenu n'est pas vide
                        \Log::info('Taille du contenu: ' . strlen($photoContent));
                        $extension = $this->getExtensionFromMimeType($photo->mime_type); // Utilisez la fonction helper que vous avez définie
                        $cidName = Str::slug($photo->nom_original, '_') . '_' . ($photo->id ?? $index) . '.' . $extension;

                        $photoData[] = [
                            'name' => $photo->nom_original,
                            'type' => $photo->mime_type,
                            'data' => $photoContent, // Binaire
                            'cid_name_for_embedding' => $cidName // Nouveau champ !
                        ];
                        
                    } else {
                        \Log::info('Photo introuvable: ' . $photo->chemin);
                    }
                }
            }

        $mailMessage = (new MailMessage)
            ->subject("[$typeLabel] Nouvelle alerte : {$this->alerte->titre}")
            ->view('emails.alerte', [
                'alerte' => $this->alerte,
                'photos' => $this->alerte->photos,
                'photoData' => $photoData
            ]);
        
        // Ajouter les photos en pièces jointes
        foreach ($photoData as $photo) {
            $filename = $photo['name'];
            $mailMessage->attachData(
                $photo['data'], 
                $filename, 
                ['mime' => $photo['type']]
            );
        }
        
        return $mailMessage;
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
