<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'userkml2025';
    protected $table = 'employees';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'emp_code',
        'firstname',
        'lastname',
        'email',
        'username',
        'password',
        'dept_id',
        'status',
        'role',
        'profile_pic',
        'signature',
        'resign_date',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'resign_date' => 'date',
    ];

    protected $appends = [
        'fullname',
        'level_user_label',
        'level_user_color',
        'level_user_icon',
        'hams_status_label',
        'hams_status_color',
        'hams_status_icon',
        'status_label',
        'status_color',
        'status_icon',
    ];

    // public function usertype()
    // {
    //     return $this->belongsTo(UserType::class, 'level_user', 'id');
    // }

    public function getUsertypeAttribute()
    {
        return (object)[
            'description' => ($this->role === 'admin' ? 'Administrator' : 'Employee')
        ];
    }

    public function getFullnameAttribute(): string
    {
        return trim(($this->attributes['firstname'] ?? '') . ' ' . ($this->attributes['lastname'] ?? ''));
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id', 'id');
    }

    // Accessors for fields no longer in appkum_user.employees
    public function getDivisionAttribute() { return (object)['division_name' => '']; }
    public function getSectionAttribute() { return (object)['section_code' => '', 'section_name' => '']; }

    // Compatibility Accessors for code still using old attribute names
    public function getPhotoUserAttribute() { return $this->attributes['profile_pic'] ?? null; }
    public function getEmployeeCodeAttribute() { return $this->attributes['emp_code'] ?? null; }
    public function getDepartmentIdAttribute() { return $this->attributes['dept_id'] ?? null; }
    public function getFirstNameAttribute() { return $this->attributes['firstname'] ?? null; }
    public function getLastNameAttribute() { return $this->attributes['lastname'] ?? null; }
    public function getPositionAttribute() { return ''; }
    public function getHrStatusAttribute() { return (int)($this->attributes['dept_id'] ?? 0) === 14 ? 1 : 0; }
    public function getLevelUserAttribute() 
    { 
        if (($this->attributes['role'] ?? '') === 'admin') return 10;
        if (in_array($this->attributes['dept_id'] ?? 0, [14, 16])) return 3;
        return 1;
    }
    
    public function setLevelUserAttribute($value)
    {
        // For compatibility with code that still sets level_user
        if ($value >= 10 || $value === 'admin') {
            $this->attributes['role'] = 'admin';
        } else {
            $this->attributes['role'] = 'staff';
        }
    }

    const STATUS_ACTIVE = 'active';
    const STATUS_RESIGN = 'resign';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => [
                'label' => 'ใช้งาน',
                'color' => 'success',
                'icon' => '<svg class="size-[1em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g fill="currentColor" stroke-linejoin="miter" stroke-linecap="butt"><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></circle><polyline points="7 13 10 16 17 8" fill="none" stroke="currentColor" stroke-linecap="square" stroke-miterlimit="10" stroke-width="2"></polyline></g></svg>',
            ],
            self::STATUS_RESIGN => [
                'label' => 'ลาออก',
                'color' => 'error',
                'icon' => '<svg class="size-[1em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 14"><g fill="currentColor"><rect x="1.972" y="11" width="20.056" height="2" transform="translate(-4.971 12) rotate(-45)" fill="currentColor" stroke-width="0"></rect><path d="m12,23c-6.065,0-11-4.935-11-11S5.935,1,12,1s11,4.935,11,11-4.935,11-11,11Zm0-20C7.038,3,3,7.037,3,12s4.038,9,9,9,9-4.037,9-9S16.962,3,12,3Z" stroke-width="0" fill="currentColor"></path></g></svg>',
            ],
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatusOptions()[$this->status]['label'] ?? '-';
    }
    public function getStatusColorAttribute()
    {
        return self::getStatusOptions()[$this->status]['color'] ?? 'default';
    }
    public function getStatusIconAttribute()
    {
        return self::getStatusOptions()[$this->status]['icon'] ?? '';
    }

    // Scope to filter active users only
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ระดับผู้ใช้งาน (Mmapped to role enum)
    const LEVEL_USER_SYSTEM_ADMIN = 'admin';
    const LEVEL_USER_OPERATION_STAFF = 'staff';
    const HAMS_STATUS_ACTIVE = 1;

    public static function getLevelUserOptions()
    {
        return [
            self::LEVEL_USER_SYSTEM_ADMIN => [
                'label' => 'System Administrator',
                'color' => 'error',
                'icon' => 'mdi mdi-shield-account',
            ],
            self::LEVEL_USER_OPERATION_STAFF => [
                'label' => 'Staff',
                'color' => 'info',
                'icon' => 'mdi mdi-account',
            ],
        ];
    }

    /**
     * Alias for getLevelUserOptions to support existing views calling getRoleOptions
     */
    public static function getRoleOptions()
    {
        return self::getLevelUserOptions();
    }

    /**
     * Options for HAMS Membership status (legacy hr_status)
     */
    public static function getHamsStatusOptions()
    {
        return [
            1 => [
                'label' => 'เป็นพนักงาน HAMS',
                'color' => 'success',
                'icon' => '<i class="fa-solid fa-check-circle mr-1"></i>',
            ],
            0 => [
                'label' => 'ไม่เป็นพนักงาน HAMS',
                'color' => 'secondary',
                'icon' => '<i class="fa-solid fa-times-circle mr-1"></i>',
            ],
        ];
    }

    public function getLevelUserLabelAttribute()
    {
        return self::getLevelUserOptions()[$this->role]['label'] ?? '-';
    }

    public function getLevelUserColorAttribute()
    {
        return self::getLevelUserOptions()[$this->role]['color'] ?? 'default';
    }

    public function getLevelUserIconAttribute()
    {
        return self::getLevelUserOptions()[$this->role]['icon'] ?? '';
    }

    // HAMS Status (compatibility for hr_status which is not in the schema, using 1 as default for Dept 14)
    public function getHamsStatusLabelAttribute() 
    { 
        $status = $this->hr_status;
        return self::getHamsStatusOptions()[$status]['label'] ?? 'ไม่ระบุ'; 
    }
    public function getHamsStatusColorAttribute() 
    { 
        $status = $this->hr_status;
        return self::getHamsStatusOptions()[$status]['color'] ?? 'secondary'; 
    }
    public function getHamsStatusIconAttribute() 
    { 
        $status = $this->hr_status;
        return self::getHamsStatusOptions()[$status]['icon'] ?? ''; 
    }

    public function hamsPermission()
    {
        return $this->hasOne(HamsPermission::class, 'user_id', 'id');
    }

    public function hamsPermissionLatestLog()
    {
        return $this->hasOne(HamsPermissionLog::class, 'target_user_id', 'id')->latestOfMany();
    }

    public function getIsHamsEditorAttribute()
    {
        return $this->hamsPermission?->is_hams_editor ?? false;
    }

    public function trainingApplies()
    {
        return $this->hasMany(TrainingApply::class, 'emp_code', 'emp_code');
    }

}
