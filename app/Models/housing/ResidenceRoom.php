<?php

namespace App\Models\housing;

use Illuminate\Database\Eloquent\Model;

class ResidenceRoom extends Model
{
    protected $table = 'residence_room';
    protected $primaryKey = 'residence_room_id';

    protected $fillable = [
        'residence_id', 'room_number', 'floor', 'residence_room_status',
        'note', 'user_createdid', 'user_updateid', 'image', 'price', 'capacity'
    ];

    public function residence()
    {
        return $this->belongsTo(Residence::class, 'residence_id', 'residence_id');
    }

    public function stays()
    {
        return $this->hasMany(ResidenceStay::class, 'residence_room_id', 'residence_room_id');
    }
}
