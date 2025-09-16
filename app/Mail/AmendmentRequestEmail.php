<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AmendmentRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $proofingJob;
    public $amendmentNotes;

    public $subject;

    public function __construct($proofingJob, $amendmentNotes, $subject)
    {
        $this->proofingJob = $proofingJob;
        $this->amendmentNotes = $amendmentNotes;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.amendment_request_email')
            ->with([
                'proofingJob' => $this->proofingJob,
                'amendmentNotes' => $this->amendmentNotes,
            ]);
    }
}
