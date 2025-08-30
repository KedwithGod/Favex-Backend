<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','purpose','code_hash','expires_at','consumed_at','email'];
    protected $casts = ['expires_at' => 'datetime', 'consumed_at' => 'datetime'];

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    public function isUsed(): bool
    {
        return !is_null($this->consumed_at);
    }
}
