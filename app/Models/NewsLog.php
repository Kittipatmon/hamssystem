<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLog extends Model
{
    protected $table = 'news_logs';
    protected $fillable = [
        'news_id',
        'user_id',
        'ip_address',
        'user_agent',
        'viewed_at',
    ];
}
