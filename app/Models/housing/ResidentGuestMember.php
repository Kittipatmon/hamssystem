<?php

namespace App\Models\housing;

use Illuminate\Database\Eloquent\Model;

class ResidentGuestMember extends Model
{
    protected $table = 'resident_guest_members';
    protected $primaryKey = 'id';

    protected $fillable = [
        'guest_request_id', 'full_name', 'age', 'relation'
    ];

    public function guestRequest()
    {
        return $this->belongsTo(ResidentGuestRequest::class, 'guest_request_id', 'resident_guest_id');
    }
}
