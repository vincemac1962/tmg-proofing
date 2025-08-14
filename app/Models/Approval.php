<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'proof_id',
        'customer_id',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'approved_at' => 'datetime'
    ];

    public function proof(): BelongsTo
    {
        return $this->belongsTo(Proof::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}