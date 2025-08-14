<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProofingJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'contract_reference',
        'designer_id',
        'proofing_company_id',
        'advert_location',
        'title',
        'description',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function designer()
    {
        return $this->belongsTo(Designer::class, 'designer_id');
    }

    public function proofs()
    {
        return $this->hasMany(Proof::class, 'job_id');
    }

    public function proofingCompany()
    {
        return $this->belongsTo(ProofingCompany::class, 'proofing_company_id');
    }
    public function activities()
    {
        return $this->hasMany(Activity::class, 'job_id');
    }
}