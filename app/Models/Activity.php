<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'user_id',
        'activity_type',
        'ip_address',
        'notes'
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proofingJob()
    {
        return $this->belongsTo(ProofingJob::class, 'job_id');
    }
}