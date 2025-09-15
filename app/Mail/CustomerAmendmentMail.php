<?php

namespace App\Mail;

use AllowDynamicProperties;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;

#[AllowDynamicProperties] class CustomerAmendmentMail extends Mailable implements ShouldQueue
{
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
    public $logo;

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
        $this->logo = $data['logo'] ?? null;
        // Ensure all required fields are set
        if (empty($this->recipient_name) || empty($this->proofingCompany) || empty($this->loginId) || empty($this->recipient_password)) {
            throw new \InvalidArgumentException('Missing required data for CustomerAmendmentMail');
        }
        // Ensure proofingCompany has an email address
        if (empty($this->proofingCompany->email_address)) {
            throw new \InvalidArgumentException('Proofing company must have an email address');
        }
    }

    public function build()
    {
        //Log::info('Logo being passed is ' . $this->logo);
        return $this->view('emails.customer_amendment')
            ->text('emails.customer_amendment_plain')
            ->replyTo($this->proofingCompany->email_address)
            ->subject('Your Amended Advertisement')
            ->attach(public_path('storage/images/' . $this->logo), [
                'as' => $this->logo,
                'mime' => 'image/svg+xml',
            ])
            ->with([
                'logo_src' => 'cid:' . $this->logo,
                'mime' => 'image/png',
            ]);
    }
}
