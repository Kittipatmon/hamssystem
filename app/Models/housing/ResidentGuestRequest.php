<?php

namespace App\Models\housing;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ResidentGuestRequest extends Model
{
    protected $table = 'resident_guest_requests';
    protected $primaryKey = 'resident_guest_id';

    protected $fillable = [
        'resident_guest_code', 'user_id', 'request_date', 'prefix',
        'first_name', 'last_name', 'position', 'department', 'section',
        'residence_type', 'room_number', 'relationship',
        'start_date', 'start_time', 'end_date', 'end_time', 'total_days',
        'send_status',
        'commander_id', 'commander_status', 'commander_comment', 'commander_date',
        'managerhams_id', 'managerhams_status', 'managerhams_comment', 'managerhams_date',
        'Committee_id', 'Committee_status', 'Committee_comment', 'Committee_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function members()
    {
        return $this->hasMany(ResidentGuestMember::class, 'guest_request_id', 'resident_guest_id');
    }
}
