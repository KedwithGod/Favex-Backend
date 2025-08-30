<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name','last_name','username','email','phone',
        'where_heard','referral_tag','password','txn_pin_hash','biometric_token',
    ];

    protected $hidden = ['password','remember_token','txn_pin_hash','biometric_token'];

    protected $casts = ['email_verified_at' => 'datetime'];
}

