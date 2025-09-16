<!-- resources/views/components/company-footer.blade.php -->
<div class="flex flex-col items-center justify-center mt-12">
    <div>
        <img src="{{ Storage::url('public/images/' . $proofingCompany['company_logo_url']) }}" alt="Company Logo" style="max-height: 100px;">
    </div>
    <div class="text-gray-800 dark:text-gray-400 mt-4 text-center">
        @if(!empty($proofingCompany['address'])) {{ $proofingCompany['address'] }}<br> @endif
        @if(!empty($proofingCompany['telephone_1'])) Tel: &nbsp;{{ $proofingCompany['telephone_1'] }}<br> @endif
        @if(!empty($proofingCompany['telephone_2'])) Tel: &nbsp;{{ $proofingCompany['telephone_2'] }}<br> @endif
        @if(!empty($proofingCompany['telephone_3'])) Tel: &nbsp;{{ $proofingCompany['telephone_3'] }}<br> @endif
        <a href="{{ $proofingCompany['web_url'] ?? '' }}">{{ $proofingCompany['web_url'] ?? '' }}</a>
    </div>
</div>
<div class="text-center text-gray-500 dark:text-gray-400 text-sm mt-8">
    &copy; {{ date('Y') }} {{ $proofingCompany['name'] ?? '' }}. All rights reserved.
</div>
