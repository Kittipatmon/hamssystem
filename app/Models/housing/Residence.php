<?php

namespace App\Models\housing;

use Illuminate\Database\Eloquent\Model;

class Residence extends Model
{
    protected $table = 'residence';
    protected $primaryKey = 'residence_id';
    public $incrementing = false;

    protected $fillable = [
        'residence_id',
        'name',
        'address',
        'blueprint_image',
        'cover_image',
        'total_floors',
        'total_rooms',
        'user_createdid',
        'user_updateid'
    ];

    public function rooms()
    {
        return $this->hasMany(ResidenceRoom::class, 'residence_id', 'residence_id');
    }
}
