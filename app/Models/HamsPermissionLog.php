<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class HamsPermissionLog extends Model
{
    protected $connection = 'mysql'; // Use default app DB
    protected $fillable = [
        'target_user_id',
        'granted_by_user_id',
        'action',
        'reason'
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            self::ensureReasonColumnExists();
        });
    }

    public static function ensureReasonColumnExists()
    {
        try {
            if (!Schema::hasColumn('hams_permission_logs', 'reason')) {
                Schema::table('hams_permission_logs', function (Blueprint $table) {
                    $table->text('reason')->nullable()->after('action');
                });
            }
        } catch (\Exception $e) {
            // Ignore if column creation fails or already exists
        }
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id', 'id');
    }

    public function grantedBy()
    {
        return $this->belongsTo(User::class, 'granted_by_user_id', 'id');
    }
}
