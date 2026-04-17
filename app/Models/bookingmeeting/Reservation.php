<?php

namespace App\Models\bookingmeeting;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Reservation extends Model
{
    protected $table = 'reservations';
    protected $primaryKey = 'reservation_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'reservation_code',
        'user_id',
        'room_id',
        'reservation_date',
        'reservation_dateend',
        'start_time',
        'end_time',
        'topic',
        'objective',
        'details',
        'participant_count',
        'requester_name',
        'attached_file',
        'status',
        'break_morning',
        'lunch',
        'dinner',
        'break_afternoon',
        'break_morning_detail',
        'lunch_detail',
        'dinner_detail',
        'break_afternoon_detail',
        'budget_file',
        'color',
        'approved_by',
        'approved_at'
    ];

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Rooms::class, 'room_id', 'room_id');
    }
}
