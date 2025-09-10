<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'company_name', 'contract_reference', 'customer_city', 'customer_country', 'contact_number', 'plain_password', 'additional_pocs','notes'
    ];

    // add boot method for cascading deletion
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($customer) {
            // Delete related models
            //$customer->user()->delete();
            if ($customer->user()->exists()) {
                $customer->user->delete();
            }
            // ToDo: Uncomment the following lines to delete related models
            //$customer->proofingJobs()->delete();
            //$customer->proofs()->delete();
            //$customer->amendments()->delete();
            //$customer->approvals()->delete();
        });
    }

    // relationship to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function proofingJobs()
    {
        return $this->hasMany(ProofingJob::class, 'customer_id', 'id');
    }

    public function proofs()
    {
        return $this->hasMany(Proof::class, 'customer_id');
    }

    public function amendments()
    {
        return $this->hasMany(Amendment::class, 'customer_id');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'customer_id');
    }
}
