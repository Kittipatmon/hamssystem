<?php

namespace App\Models\parking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_registration',
        'details',
        'slot_id',
        'dept_id',
        'checkin_datetime',
        'checkout_datetime',
        'manager_approval',
        'manager_approved_by',
        'manager_approved_at',
        'hams_status',
        'hams_acknowledged_by',
        'hams_acknowledged_at',
        'status',
    ];

    protected $casts = [
        'checkin_datetime' => 'datetime',
        'checkout_datetime' => 'datetime',
        'manager_approved_at' => 'datetime',
        'hams_acknowledged_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class, 'dept_id');
    }

    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_approved_by');
    }

    public function hamsAckBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'hams_acknowledged_by');
    }

    public function slot()
    {
        return $this->belongsTo(ParkingSlot::class, 'slot_id');
    }
}
