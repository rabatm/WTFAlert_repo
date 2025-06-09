<?php

namespace App\Mail;

use App\Models\Alerte;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NouvelleAlerteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $alerte;

    public function __construct(Alerte $alerte)
    {
        // Charger les relations nécessaires
        $this->alerte = $alerte->load(['photos', 'habitant']);
    }

    public function build()
    {
        \Log::info('Building email for alert #' . $this->alerte->id . ' with ' . count($this->alerte->photos) . ' photos');
        
        $mail = $this->subject('Nouvelle alerte - ' . $this->alerte->titre)
                     ->view('emails.nouvelle-alerte')
                     ->with([
                         'alerte' => $this->alerte,
                     ]);

        // Ajouter les photos en pièces jointes si elles existent
        if ($this->alerte->photos && count($this->alerte->photos) > 0) {
            foreach ($this->alerte->photos as $index => $photo) {
                \Log::info('Processing photo attachment: ' . $photo->chemin);
                if (Storage::disk('private')->exists($photo->chemin)) {
                    $mail->attach(
                        Storage::disk('private')->path($photo->chemin),
                        [
                            'as' => 'photo_' . ($index + 1) . '_' . $photo->nom_original,
                            'mime' => $photo->mime_type,
                        ]
                    );
                    \Log::info('Photo attached successfully: ' . $photo->nom_original);
                } else {
                    \Log::warning('Photo file not found: ' . $photo->chemin);
                }
            }
        } else {
            \Log::info('No photos to attach for alert #' . $this->alerte->id);
        }

        return $mail;
    }
}
