<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertisement Proof</title>
    <!-- Google Fonts links -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">

    <style>
        .content {
            width: 80%;
            max-width: 80%;
            min-height: 50%;
            background-color: #F5F5F5;
            margin: 0 auto;
            padding: 20px;
            text-align: center; /* Center child elements */
            border-radius: 16px;
        }

        .header-image {
            width: 80%;
            height: auto;
            margin: 0 auto 20px;
            display: block;
        }

        .body-container {
            width: 80%;
            max-width: 80%;
            min-height: 50%;
            background-color: #F5F5F5;
            margin: 0 auto;
            padding: 20px;
            text-align: center; /* Center child elements */
        }

        .body-text {
            font-size: 16px;
            line-height: 1.5;
            color: #333;
            font-family: 'Open Sans', sans-serif;
            text-align: left;
        }
    </style>
</head>
<!-- CSS -->

<body>
<div class="content" style="width: 80%; max-width: 80%; min-height: 50%; background-color: #F5F5F5; margin: 0 auto; padding: 20px; text-align: center; border-radius: 16px;">
        <div class="header-image">
            <!-- ToDo : create bas64 image for the logo -->
            <img src="{{ $logo_src }}" alt="Logo">
        </div>
        <div class="body-container">
            <h1 style="font-family: 'Open Sans', sans-serif">{{ $subject }}</h1>
            <div class="body-text">

                <p><strong>Advert Location:</strong> {{ $advert_location }}</p>
                <p><strong>Contract Reference:</strong> {{ $contract_reference }}</p>
                <p><strong>Company Name:</strong> {{ $company_name }}</p>
                <p>Dear {{ $recipient_name }},</p>

                <p>We are pleased to present the initial proof of you advertisement for display at the above site(s).
                    It's an exciting opportunity to showcase your brand to a wide audience, and we want to ensure that your
                    advertisement is nothing but perfect.</p>

                <p>To view your proof please click on the link below and enter the username and password we've given you.
                    We understand that perfection is a process, so please do not hesitate to share your thoughts and
                    suggestions on any changes you would like us to make. We are committed to working with you to create an
                    advertisement that meets your exact needs.</p>

                <p><strong>Proofing Website:</strong> {{ $login_url }}</p>
                <p><strong>Username:</strong> {{ $recipient_email }}</p>
                <p><strong>Password:</strong> {{ $recipient_password }}</p>

                @if(!empty($notes))
                    <p>The designer who created you advertisement has added the following notes to the proof:</p>
                    <p><strong>Designer Notes:</strong> {{ $notes }}</p>
                @endif

                <p>If you have any questions or concerns regarding the design process of your advertisement, we are always
                    available to assist you. Simply reply to this email, or call us on the number below, and we will be happy to help.</p>

                <p>Please note that if we do not hear back from you within 28 days, we will assume that the advertisement is
                    correct on your behalf.</p>

                <p>Thank you for choosing us to be your partner in promoting your brand. We look forward to hearing back from you
                    and finalizing your advertisement.</p>
            </div>
            <p>
            <div style="display: flex; flex-direction: row; align-items: flex-start; justify-content: center; gap: 20px; padding: 20px 0; margin: 0 auto;">
                <!-- Left Column -->
                <div style="flex: 0 0 auto; padding-right: 20px;">
                    <img src="{{ $logo_src }}" alt="Company Logo" style="max-width: 75px;">
                </div>

                <!-- Right Column -->
                <div style="flex: 1; text-align: left;">
                    {{ $proofingCompany->email_signatory }}<br>
                    {{ $proofingCompany->signatory_role }}<br>
                    @if($proofingCompany->telephone_1) {{ $proofingCompany->telephone_1 }}<br> @endif
                    @if($proofingCompany->telephone_2) {{ $proofingCompany->telephone_2 }}<br> @endif
                    @if($proofingCompany->telephone_3) {{ $proofingCompany->telephone_3 }}<br> @endif
                    <a href="{{ $proofingCompany->web_url }}">{{ $proofingCompany->web_url }}</a>
                </div>
            </div>
            </p>

        </div>

</div>

</body>
</html>
