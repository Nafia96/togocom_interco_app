<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FactureMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfPath;

    public function __construct($pdfPath)
    {
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject('Votre facture mensuelle')
                    ->view('emails.facture')
                    ->attach($this->pdfPath, [
                        'as' => basename($this->pdfPath),
                        'mime' => 'application/pdf',
                    ]);
    }
}
