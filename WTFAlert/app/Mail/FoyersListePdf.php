<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FoyersListePdf extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;
    public $filename;

    public function __construct(string $pdfContent, string $filename)
    {
        $this->pdfContent = $pdfContent;
        $this->filename = $filename;
    }

    public function build()
    {
        return $this->subject('Liste des foyers')
            ->view('emails.foyers_liste')
            ->attachData($this->pdfContent, $this->filename, [
                'mime' => 'application/pdf',
            ]);
    }
}
