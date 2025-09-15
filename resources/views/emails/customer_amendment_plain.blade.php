Advert Location: {{ $advert_location }}
Contract Reference: {{ $contract_reference }}
Company Name: {{ $company_name }}

Dear {{ $recipient_name }},

Your advertisement has been amended per your instructions.

To view your amended proof please click on the link below and enter the username and password we've given you. We understand that perfection is a process, so please do not hesitate to share your thoughts and suggestions on any changes you would like us to make. We are committed to working with you to create an advertisement that meets your exact needs.

Proofing Website: {{ $login_url }}
Username: {{ $recipient_email }}
Password: {{ $password }}

@if(!empty($notes))
The designer who created you advertisement has added the following notes to the proof:
Designer Notes:{ $notes }}
@endif

If you have any questions or concerns regarding the design process of your advertisement, we are always available to assist you. Simply reply to this email, or call us on the number below, and we will be happy to help.

Please note that if we do not hear back from you within 28 days, we will assume that the advertisement is correct on your behalf.

Thank you for choosing us to be your partner in promoting your brand. We look forward to hearing back from you and finalizing your advertisement.

{{ $proofingCompany->email_signatory }}
{{ $proofingCompany->signatory_role }}
