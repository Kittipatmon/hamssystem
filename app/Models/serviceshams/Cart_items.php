<?php

namespace App\Models\serviceshams;

use Illuminate\Database\Eloquent\Model;

class Cart_items extends Model
{
     protected $table = 'cart_items';
    protected $primaryKey = 'cart_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true; // uses created_at, updated_at

    protected $fillable = [
        'cart_item_id',
        'cart_code',
        'cart_name',
        'cart_quantity',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item()
    {
        return $this->belongsTo(Items::class, 'cart_item_id', 'item_id');
    }


}
