<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $primaryKey = 'news_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'newto',
        'title',
        'content',
        'published_date',
        'is_active',
        'image_path',
        'views_count',
    ];

    protected $casts = [
        'published_date' => 'datetime',
        'is_active' => 'boolean',
    ];
}
