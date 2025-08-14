<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amendment extends Model
{
    use HasFactory;

    protected $fillable = ['proof_id', 'customer_id', 'amendment_notes', 'contract_reference'];

    public function proof()
    {
        return $this->belongsTo(Proof::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}