<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'notes',
        'active',
    ];

    /**
     * Define the relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the ProofingJob model.
     */
    public function proofingJobs()
    {
        return $this->hasMany(ProofingJob::class);
    }
}