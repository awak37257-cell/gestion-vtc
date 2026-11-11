<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidatureRejetee extends Mailable
{
    use Queueable, SerializesModels;

    public $candidature;

    public function __construct(Candidature $candidature)
    {
        $this->candidature = $candidature;
    }

    public function build()
    {
        return $this->subject('Suivi de votre candidature VTC — TIHE\'S Group')
                    ->view('emails.candidature_rejetee');
    }
}