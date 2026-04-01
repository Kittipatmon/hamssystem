<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcement';
    protected $primaryKey = 'announcement_id';

    protected $fillable = [
        'title',
        'content',
        'published_date',
        'image_path',
        'is_urgent',
    ];

    protected $casts = [
        'is_urgent' => 'boolean',
        'published_date' => 'date',
    ];
}
