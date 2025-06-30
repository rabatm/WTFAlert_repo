<?php

namespace App\Mail;

use App\Models\DemandeModification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NouvelleDemandeModificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $adminEmails;

    public function __construct(DemandeModification $demande, array $adminEmails = [])
    {
        $this->demande = $demande->load(['user', 'foyer', 'habitant']);
        $this->adminEmails = $adminEmails;
    }

    public function build()
    {
        $subject = 'Nouvelle demande de modification - ' . 
                  ucfirst(str_replace('_', ' ', $this->demande->type)) . 
                  ' - ' . $this->demande->foyer->nom;
        
        return $this->subject($subject)
                   ->view('emails.nouvelle-demande-modification')
                   ->with([
                       'demande' => $this->demande,
                   ]);
    }
}
