<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $table = 'policy';
    protected $primaryKey = 'policy_id';


    protected $fillable = [
        'title',
        'content',
        'type',
        'order',
    ];

    /**
     * Scope a query to only include policies.
     */
    public function scopePolicies($query)
    {
        return $query->where('type', 'policy');
    }

    /**
     * Scope a query to only include operations.
     */
    public function scopeOperations($query)
    {
        return $query->where('type', 'operation');
    }
}
