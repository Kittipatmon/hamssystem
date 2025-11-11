<?php

namespace App\Models\serviceshams;

use Illuminate\Database\Eloquent\Model;

class Items_type extends Model
{
     protected $table = 'items_type';
    protected $primaryKey = 'item_type_id ';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true; // uses created_at, updated_at

    protected $fillable = [
        'name',
        'description',
        'status',
    ];
}
