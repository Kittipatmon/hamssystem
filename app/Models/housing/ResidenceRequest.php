<?php

namespace App\Models\housing;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ResidenceRequest extends Model
{
    protected $connection = 'mysql';
    protected $table = 'residence_requests';
    protected $primaryKey = 'id';

    protected $fillable = [
        'requests_code',
        'request_date',
        'site',
        'title',
        'first_name',
        'last_name',
        'position',
        'department',
        'section',
        'age_work',
        'phone',
        'marital_status',
        'address_original',
        'address_original_subdistrict',
        'address_original_district',
        'address_original_province',
        'address_current',
        'address_current_subdistrict',
        'address_current_district',
        'address_current_province',
        'current_house_type',
        'spouse_name',
        'spouse_occupation',
        'spouse_phone',
        'workplace_spouse',
        'number_of_residents',
        'residence_reason',
        'requests_file',
        'send_status',
        'user_id',
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
        'Committee_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function dependents()
    {
        return $this->hasMany(ResidenceDependent::class, 'request_id', 'id');
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
}
