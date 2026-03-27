<?php

namespace App\Models\housing;

use Illuminate\Database\Eloquent\Model;

class ResidenceStay extends Model
{
    protected $table = 'residence_stay';
    protected $primaryKey = 'residence_stay_id';

    protected $fillable = [
        'residence_room_id', 'residence_resident_id', 'check_in', 'check_out',
        'is_current', 'tel_phone', 'note', 'user_createdid', 'user_updateid',
        'status', 'send_status', 'reason_leave', 'residence_stay_date'
    ];

    public function room()
    {
        return $this->belongsTo(ResidenceRoom::class, 'residence_room_id', 'residence_room_id');
    }
}
