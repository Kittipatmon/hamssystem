<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class HamsPermission extends Model
{
    protected $connection = 'mysql'; // Use default app DB
    protected $fillable = ['user_id', 'is_hams_editor', 'role'];

    protected static function booted()
    {
        static::saving(function ($model) {
            self::ensureRoleColumnExists();
        });
    }

    public static function ensureRoleColumnExists()
    {
        try {
            if (!Schema::hasColumn('hams_permissions', 'role')) {
                Schema::table('hams_permissions', function (Blueprint $table) {
                    $table->string('role')->default('viewer')->after('is_hams_editor');
                });
            }
        } catch (\Exception $e) {
            // Ignore if column creation fails or already exists
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
