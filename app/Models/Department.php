<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    // protected $connection = 'userkml';
    protected $connection = 'userkml2025';
    protected $table = 'departments';
    

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'manager_id',
    ];

    // Mapping for existing views
    public function getDeptIdAttribute() { return $this->id; }
    public function getDepartmentNameAttribute() { return $this->name; }
    public function getDepartmentFullnameAttribute() { return $this->name; }
    public function getDepartmentStatusAttribute() { return 0; } // Default to Active (0)

    public function users()
    {
        return $this->hasMany(User::class, 'dept_id', 'id');
    }

    // Status mapping (Static as schema doesn't show status column)
    public function getStatusLabelAttribute() { return 'ใช้งาน'; }
    public function getStatusColorAttribute() { return 'success'; }
    public function getStatusIconAttribute() { return ''; }

}
