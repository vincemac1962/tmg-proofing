<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'role', 'password', 'access_level', 'is_active', 'created_by',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
