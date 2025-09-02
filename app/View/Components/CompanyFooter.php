<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CompanyFooter extends Component
{
    public $proofingCompany;

    public function __construct($proofingCompany)
    {
        $this->proofingCompany = $proofingCompany;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.company-footer');
    }
}
