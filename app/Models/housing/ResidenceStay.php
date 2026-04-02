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

    public function resident()
    {
        // This is specifically for the Agreement (QF-HAMS-03)
        return $this->belongsTo(ResidenceAgreement::class, 'residence_resident_id', 'user_id')
            ->where('send_status', 3) // Success
            ->with('user')
            ->orderBy('created_at', 'desc');
    }

    public function latestRequest()
    {
        // Fallback for names if agreement is not yet finished
        return $this->belongsTo(ResidenceRequest::class, 'residence_resident_id', 'user_id')
            ->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'residence_resident_id', 'id');
    }
}
