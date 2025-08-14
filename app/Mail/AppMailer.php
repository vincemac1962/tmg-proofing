<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     */
    public function build()
    {
        return $this->from('proofing@time-cdn.co.uk', 'Proofing Team')
            ->replyTo('proofing@time-cdn.co.uk', 'Proofing Team')
            ->subject($this->data['subject'])
            ->text('emails.proofing_email_plain')
            ->view('emails.proofing_email')
            ->with('data', $this->data);
    }
}