<p>Dear Team,</p>

<p>Approval has been received for the following proof:</p>

<ul>
    <li><strong>Contract Reference:</strong> {{ $proofingJob->contract_reference }}</li>
    <li><strong>Company Name:</strong> {{ $proofingJob->customer->company_name }}</li>
</ul>

<p><strong>Approved by:</strong></p>
<p>{{ $approved_by }}</p>

<p><strong>Date:</strong></p>
<p>{{ $approved_at  }}</p>
