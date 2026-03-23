<?php

namespace App\Models\bookingcar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class BookingCar extends Model
{
    use HasFactory;

    protected $table = 'vehicle_bookings';
    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'booking_code',
        'user_id',
        'vehicle_id',
        'bookings_date',
        'booking_date',
        'start_time',
        'end_time',
        'destination',
        'district',
        'province',
        'requester_name',
        'passenger_count',
        'purpose',
        'mileage_before',
        'mileage_after',
        'note_returning',
        'attachment',
        'attachment_going',
        'attachment_returning',
        'returned_at',
        'return_status',
        'status',
        'approved_by',
        'approved_status',
        'approved_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }
}
