<?php

namespace App\Models\housing;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ResidenceLeave extends Model
{
    protected $table = 'residence_leaves';
    protected $primaryKey = 'residence_leaves_id';

    protected $fillable = [
        'residence_leaves_code', 'user_id', 'residence_room_id', 'request_date', 'prefix',
        'first_name', 'last_name', 'position', 'department', 'section',
        'residence_type', 'room_number', 'floor', 'move_out_date', 'reason',
        'send_status',
        'managerhams_id', 'managerhams_status', 'managerhams_comment', 'managerhams_date',
        'Committee_id', 'Committee_status', 'Committee_comment', 'Committee_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function managerHams()
    {
        return $this->belongsTo(User::class, 'managerhams_id', 'id');
    }

    public function committee()
    {
        return $this->belongsTo(User::class, 'Committee_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(ResidenceRoom::class, 'residence_room_id', 'residence_room_id');
    }
}
