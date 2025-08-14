<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'customer_id',
        'recipient_email',
        'subject',
        'body',
    ];

    public function proofingJob()
    {
        return $this->belongsTo(ProofingJob::class, 'job_id');
    }
}