<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $proofingJob;
    public $approvedBy;

    public $approvedAt;

    public function __construct($proofingJob, $approvedBy, $approvedAt)
    {
        $this->proofingJob = $proofingJob;
        $this->approvedBy = $approvedBy;
        $this->approvedAt = $approvedAt;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.approval_email')
            ->with([
                'proofingJob' => $this->proofingJob,
                'approved_by' => $this->approvedBy,
                'approved_at' => $this->approvedAt,
            ]);
    }
}
