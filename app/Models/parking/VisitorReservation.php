<?php

namespace App\Models\parking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_name',
        'company',
        'phone',
        'car_registration',
        'contact_user_id',
        'contact_details',
        'checkin_datetime',
        'checkout_datetime',
        'duration_hours',
        'slot_id',
        'status',
        'is_locked',
        'manager_approval',
        'manager_approved_by',
        'hams_status',
        'hams_acknowledged_by',
    ];

    protected $casts = [
        'checkin_datetime' => 'datetime',
        'checkout_datetime' => 'datetime',
        'is_locked' => 'boolean',
    ];

    public function contactUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'contact_user_id');
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
