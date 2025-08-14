<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\ProofingCompany;
use App\Models\ProofingJob;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Prevent redirect loops
        if ($user->role === 'customer' && $request->route()->getName() === 'customers.landing') {
            return $next($request);
        }

        if (in_array($user->role, ['admin', 'super_admin', 'designer']) && $request->route()->getName() === 'dashboard') {
            return $next($request);
        }

        // Redirect based on role
        if ($user->role === 'customer') {
            // Retrieve the customer record for the logged-in user
            $customer = Customer::where('user_id', $user->id)->first();

            // Retrieve all proofing jobs for the customer
            $proofingJobs = ProofingJob::where('customer_id', $customer->id)->get();

            // Find the highest proofing_company_id
            $highestProofingCompanyId = $proofingJobs->max('proofing_company_id');

            // Retrieve the company logo URL
            $proofingCompany = ProofingCompany::find($highestProofingCompanyId);
            $companyLogoUrl = $proofingCompany ? $proofingCompany->company_logo_url : null;

            // Store the company logo URL in the session
            session(['company_logo_url' => $companyLogoUrl]);

            // redirect to the customer's landing page
            return redirect()->route('customers.landing');

        } elseif (in_array($user->role, ['admin', 'super_admin', 'designer'])) {
            return redirect()->route('dashboard');
        }

        // Deny access for other roles
        abort(403, 'Unauthorized access.');
    }
}