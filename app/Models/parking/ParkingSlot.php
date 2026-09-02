<?php

namespace App\Models\parking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'slot_number',
        'status',
        'pos_x',
        'pos_y',
        'rotation',
        'width',
        'height',
    ];

    protected $casts = [
        'pos_x' => 'float',
        'pos_y' => 'float',
        'rotation' => 'float',
        'width' => 'float',
        'height' => 'float',
    ];

    public function zone()
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    public function employeeParkings()
    {
        return $this->hasMany(EmployeeParking::class, 'slot_id');
    }

    public function visitorReservations()
    {
        return $this->hasMany(VisitorReservation::class, 'slot_id');
    }

    public function employeeReservations()
    {
        return $this->hasMany(EmployeeReservation::class, 'slot_id');
    }
}
