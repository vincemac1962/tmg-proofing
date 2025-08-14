<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProofingCompany extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'telephone_1',
        'telephone_2',
        'telephone_3',
        'email_address',
        'web_url',
        'email_signatory',
        'signatory_role',
        'company_logo_url',
        'colour_split',
        'active',
    ];
    // relationship to proofing jobs
    public function proofingJobs()
    {
        return $this->hasMany(ProofingJob::class);
    }
}
