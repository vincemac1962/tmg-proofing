<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomerReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $contract_reference;
    public $recipient_name;
    public $recipient_email;
    public $recipient_password;
    public $proofingCompany;
    public $advert_location;
    public $company_name;
    public $loginId;
    public $password;
    public $login_url;
    public $subject;
    public $notes;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->contract_reference = $data['contract_reference'] ?? null;
        $this->recipient_name = $data['recipient_name'] ?? null;
        $this->recipient_email = $data['recipient_email'] ?? null;
        $this->recipient_password = $data['recipient_password'] ?? null;
        $this->proofingCompany = $data['proofingCompany'] ?? null;
        $this->advert_location = $data['advert_location'] ?? null;
        $this->company_name = $data['company_name'] ?? null;
        $this->loginId = $data['recipient_email'] ?? null;
        $this->login_url = config('app.base_login_url') . '?proofing_company=' . ($this->proofingCompany ? $this->proofingCompany->id : '');
        $this->subject = 'Your Proofing Login Details';
        $this->notes = $data['notes'] ?? null;

        // Log missing fields
        $missingFields = [];
        if (empty($this->recipient_name)) $missingFields[] = 'recipient_name';
        if (empty($this->proofingCompany)) $missingFields[] = 'proofingCompany';
        if (empty($this->loginId)) $missingFields[] = 'loginId';
        if (empty($this->recipient_password)) $missingFields[] = 'recipient_password';
        if ($this->proofingCompany && empty($this->proofingCompany->email_address)) $missingFields[] = 'proofingCompany.email_address';

        if (!empty($missingFields)) {
            Log::error('Missing required data for CustomerReminderMail: ' . implode(', ', $missingFields));
            throw new \InvalidArgumentException('Missing required data for CustomerReminderMail: ' . implode(', ', $missingFields));
        }
    }

    public function build()
    {
        return $this->view('emails.reminder_email')
            ->text('emails.reminder_email_plain')
            ->replyTo($this->proofingCompany->email_address)
            ->subject('Your Artwork Reminder');
    }
}
