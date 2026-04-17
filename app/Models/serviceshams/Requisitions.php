<?php

namespace App\Models\serviceshams;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\serviceshams\Requisition_items;
use App\Models\User;

class Requisitions extends Model
{
    use HasFactory;

    protected $table = 'requisitions';
    protected $primaryKey = 'requisitions_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'requester_id',
        'request_date',
        'status',
        'remarks',
        'request_number',
        'approve_id',
        'approve_status',
        'approve_comment',
        'approve_date',
        'total_price',
        'packing_staff_id',
        'packing_staff_status',
        'packing_staff_comment',
        'packing_staff_date',
        'requisitions_code',
        'requester_comment',
        'commander_id',
        'commander_status',
        'commander_comment',
        'commander_date',
        'managerhams_id',
        'managerhams_status',
        'managerhams_comment',
        'managerhams_date',
        'Committee_id',
        'Committee_status',
        'Committee_comment',
        'Committee_date',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'approve_date' => 'datetime',
        'packing_staff_date' => 'datetime',
        'total_price' => 'decimal:2',
        'approve_status' => 'integer',
        'packing_staff_status' => 'integer',
        'commander_status' => 'integer',
        'managerhams_status' => 'integer',
        'Committee_status' => 'integer',
        'commander_date' => 'datetime',
        'managerhams_date' => 'datetime',
        'Committee_date' => 'datetime',
    ];

       public function user()
    {
        return $this->belongsTo(User::class, 'requester_id', 'id'); 

    }

    public function approve_user()
    {
        return $this->belongsTo(User::class, 'approve_id', 'id'); 
    }

    public function packing_staff()
    {
        return $this->belongsTo(User::class, 'packing_staff_id', 'id'); 
    }

    public function commander()
    {
        return $this->belongsTo(User::class, 'commander_id', 'id');
    }

    public function managerHams()
    {
        return $this->belongsTo(User::class, 'managerhams_id', 'id');
    }

    public function committee()
    {
        return $this->belongsTo(User::class, 'Committee_id', 'id');
    }

    public function requisition_items() {
        return $this->hasMany(Requisition_items::class, 'requisition_id', 'requisitions_id');
    }

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_END_PROGRESS = 'endprogress';

    public const statusOptions = [
        self::STATUS_PENDING => [
            'label' => 'รอดำเนินการ',
            'color' => 'warning',
            'class' => 'badge bg-warning',
            'icon' => 'fa fa-clock',
        ],
        self::STATUS_APPROVED => [
            'label' => 'รอดำเนินการจัดอุปกรณ์',
            'color' => 'success',
            'class' => 'badge bg-success',
            'icon' => 'fa fa-check',
        ],
        self::STATUS_REJECTED => [
            'label' => 'ไม่อนุมัติ',
            'color' => 'danger',
            'class' => 'badge bg-error',
            'icon' => 'fa fa-times',
        ],
        self::STATUS_RETURNED => [
            'label' => 'ส่งคืน',
            'color' => 'secondary',
            'class' => 'badge bg-secondary',
            'icon' => 'fa fa-undo',
        ],
        self::STATUS_CANCELLED => [
            'label' => 'ยกเลิก',
            'color' => 'dark',
            'class' => 'badge bg-error',
            'icon' => 'fa fa-ban',
        ],
        self::STATUS_END_PROGRESS => [
            'label' => 'ดำเนินการเสร็จสิ้น',
            'color' => 'info',
            'class' => 'badge bg-success',
            'icon' => 'fa fa-check',
        ],
    ];

    public function getStatusLabelAttribute()
    {
        return self::statusOptions[$this->status]['label'] ?? 'Unknown';
    }

    public function getStatusClassAttribute()
    {
        return self::statusOptions[$this->status]['class'] ?? 'badge bg-secondary';
    }

    public function getStatusIconAttribute()
    {
        return self::statusOptions[$this->status]['icon'] ?? 'fa fa-question';
    }


    public const APPROVE_STATUS_PENDING = 0;
    public const APPROVE_STATUS_APPROVED = 1;
    public const APPROVE_STATUS_REJECTED = 2;


    public const attributeOptions = [
        'approve_status' => [
            'label' => [
                self::APPROVE_STATUS_PENDING => 'รออนุมัติ',
                self::APPROVE_STATUS_APPROVED => 'อนุมัติ',
                self::APPROVE_STATUS_REJECTED => 'ไม่อนุมัติ'
            ],
            'color' => [
                self::APPROVE_STATUS_PENDING => 'warning',
                self::APPROVE_STATUS_APPROVED => 'success',
                self::APPROVE_STATUS_REJECTED => 'danger'
            ],
            'class' => [
                self::APPROVE_STATUS_PENDING => 'badge bg-warning',
                self::APPROVE_STATUS_APPROVED => 'badge bg-success',
                self::APPROVE_STATUS_REJECTED => 'badge bg-danger'
            ],
            'icon' => [
                self::APPROVE_STATUS_PENDING => 'fa fa-clock',
                self::APPROVE_STATUS_APPROVED => 'fa fa-check',
                self::APPROVE_STATUS_REJECTED => 'fa fa-times'
            ]
        ],
    ];

    public function getApproveStatusLabelAttribute()
    {
        return self::attributeOptions['approve_status']['label'][$this->approve_status] ?? 'Unknown';
    }

    // Approver Status Labels
    public function getCommanderStatusLabelAttribute()
    {
        return self::attributeOptions['approve_status']['label'][$this->commander_status] ?? 'Unknown';
    }

    public function getManagerhamsStatusLabelAttribute()
    {
        return self::attributeOptions['approve_status']['label'][$this->managerhams_status] ?? 'Unknown';
    }

    public function getCommitteeStatusLabelAttribute()
    {
        return self::attributeOptions['approve_status']['label'][$this->Committee_status] ?? 'Unknown';
    }



    // packing_staff_status
    public const PACKING_STATUS_PENDING = 0;
    public const PACKING_STATUS_APPROVED = 1;   
    public const PACKING_STATUS_CANCELLED = 2;
    public const packingStatusOptions = [
        self::PACKING_STATUS_PENDING => [
            'label' => 'รอดำเนินการจัดอุปกรณ์',
            'color' => 'warning',
            'class' => 'badge bg-warning',
            'icon' => 'fa fa-clock',
        ],
        self::PACKING_STATUS_APPROVED => [
            'label' => 'จัดอุปกรณ์เรียบร้อย',
            'color' => 'success',
            'class' => 'badge bg-success',
            'icon' => 'fa fa-check',
        ],
        self::PACKING_STATUS_CANCELLED => [
            'label' => 'ยกเลิกการจัดส่ง',
            'color' => 'danger',
            'class' => 'badge bg-danger',
            'icon' => 'fa fa-times',
        ],
    ];
    public function getPackingStatusLabelAttribute()
    {
        if ($this->status === self::STATUS_CANCELLED) {
            return 'ถูกยกเลิกแล้ว';
        }
        return self::packingStatusOptions[$this->packing_staff_status]['label'] ?? 'Unknown';
    }
    public function getPackingStatusClassAttribute()
    {
        if ($this->status === self::STATUS_CANCELLED) {
            return 'bg-red-100 text-red-600 border-red-200';
        }
        return self::packingStatusOptions[$this->packing_staff_status]['class'] ?? 'badge bg-secondary';
    }
    public function getPackingStatusIconAttribute()
    {
        if ($this->status === self::STATUS_CANCELLED) {
            return 'fa-solid fa-ban';
        }
        return self::packingStatusOptions[$this->packing_staff_status]['icon'] ?? 'fa fa-question';
    }


}
