<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $connection = 'userkml2025';
    protected $table = 'user_types';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'type_code',
        'type_name',
        'status',
        'description',
    ];

    protected $casts = [
        'status' => 'integer',
    ];
}
