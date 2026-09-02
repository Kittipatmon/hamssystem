<?php

namespace App\Models\parking;

use Illuminate\Database\Eloquent\Model;

class ParkingMapElement extends Model
{
    protected $fillable = [
        'zone_id',
        'type',
        'content',
        'pos_x',
        'pos_y',
        'rotation',
        'scale',
        'color',
    ];

    protected $casts = [
        'pos_x' => 'float',
        'pos_y' => 'float',
        'rotation' => 'float',
        'scale' => 'float',
    ];

    public function zone()
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }
}
