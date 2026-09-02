<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class IpBlacklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'reason',
        'banned_by',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }
}
