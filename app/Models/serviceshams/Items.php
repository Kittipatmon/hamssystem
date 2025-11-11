<?php

namespace App\Models\serviceshams;

use Illuminate\Database\Eloquent\Model;
use App\Models\serviceshams\Items_type;

class Items extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'item_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true; // uses created_at, updated_at

    protected $fillable = [
        'item_code',
        'name',
        'description',
        'quantity',
        'items_per_pack',
        'type_id',
        'status',
        'item_pic',
        'per_unit',
        'per_pack',
        'send_status',
    ];

    protected $casts = [
        'quantity'       => 'int',
        'items_per_pack' => 'int',
        'type_id'        => 'int',
        'status'         => 'int',
        'per_unit'       => 'int',
        'per_pack'       => 'int',
        'send_status'    => 'int',
    ];

        public function items_type()
    {
        return $this->belongsTo(Items_type::class, 'type_id', 'item_type_id');
    }
}
