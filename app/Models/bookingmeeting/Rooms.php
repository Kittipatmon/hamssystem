<?php

namespace App\Models\BookingMeeting;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'room_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'room_name',
        'room_type',
        'capacity',
        'location',
        'floor',
        'images',
        'description',
        'status',
        'has_projector',
        'has_video_conf',
    ];

    protected $casts = [
        'capacity'       => 'integer',
        'status'         => 'integer',
        'has_projector'  => 'boolean',
        'has_video_conf' => 'boolean',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    // Optional JSON helpers if images stored as JSON
    public function getImagesAttribute($value)
    {
        if (is_null($value) || $value === '') return [];
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [$value];
    }

    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = is_array($value) ? json_encode($value) : $value;
    }

    // Scope example
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
