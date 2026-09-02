<?php

namespace App\Models\parking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'building',
        'floor',
        'zone',
        'total_slots',
    ];

    public function slots()
    {
        return $this->hasMany(ParkingSlot::class, 'zone_id');
    }
}
