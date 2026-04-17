<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HamsPermission extends Model
{
    protected $connection = 'mysql'; // Use default app DB
    protected $fillable = ['user_id', 'is_hams_editor'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
