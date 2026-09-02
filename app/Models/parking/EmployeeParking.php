<?php

namespace App\Models\parking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeParking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_registration',
        'slot_id',
        'time_in',
        'time_out',
        'status',
    ];

    protected $casts = [
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function slot()
    {
        return $this->belongsTo(ParkingSlot::class, 'slot_id');
    }
}
