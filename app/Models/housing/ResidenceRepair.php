<?php

namespace App\Models\housing;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ResidenceRepair extends Model
{
    protected $table = 'residence_repairs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'repair_code',
        'user_id',
        'residence_room_id',
        'title',
        'description',
        'images',
        'technician_id',
        'status',
        'admin_comment',
        'repair_date',
        'completion_date',
        'technician_images',
        'technician_note',
    ];

    protected $casts = [
        'images' => 'array',
        'technician_images' => 'array',
        'repair_date' => 'date',
        'completion_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(ResidenceRoom::class, 'residence_room_id', 'residence_room_id');
    }
}
