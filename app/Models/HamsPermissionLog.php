<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HamsPermissionLog extends Model
{
    protected $connection = 'mysql'; // Use default app DB
    protected $fillable = ['target_user_id', 'granted_by_user_id', 'action'];

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id', 'id');
    }

    public function grantedBy()
    {
        return $this->belongsTo(User::class, 'granted_by_user_id', 'id');
    }
}
