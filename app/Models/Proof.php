<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proof extends Model
{
    use HasFactory;

    protected $fillable = ['job_id', 'file_path', 'notes', 'proof_sent'];
    protected $casts = [
        'proof_sent' => 'datetime',
    ];

    public function proofingJob()
    {
        return $this->belongsTo(ProofingJob::class, 'job_id');
    }
}