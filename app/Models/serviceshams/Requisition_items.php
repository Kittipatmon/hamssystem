<?php
// app/Models/Serviceshams/RequisitionItem.php

namespace App\Models\Serviceshams;

use Illuminate\Database\Eloquent\Model;

class Requisition_items extends Model
{
    protected $table = 'requisition_items';
    protected $primaryKey = 'requisitionitem_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'requisition_id',
        'item_id',
        'quantity',
        'unit',
        'total_price',
        'check_item',
    ];

    protected $casts = [
        'requisition_id' => 'int',
        'item_id'        => 'int',
        'quantity'       => 'int',
        'total_price'    => 'decimal:2',
        'check_item'     => 'boolean',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    public const CHECK_ITEM_NOT_ARRANGED = 0; 
    public const CHECK_ITEM_ARRANGED = 1;     

    public const CHECK_ITEM_LABELS = [
        self::CHECK_ITEM_NOT_ARRANGED => 'ยังไม่ได้จัด',
        self::CHECK_ITEM_ARRANGED => 'จัดเรียบร้อย',
    ];

    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id', 'item_id');
    }

}
