<?php

namespace App\Models\housing;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ResidenceAgreement extends Model
{
    protected $connection = 'mysql';
    protected $table = 'residence_agreements';
    protected $primaryKey = 'agreement_id';

    protected $fillable = [
        'agreement_code',
        'user_id',
        'agreement_date',
        'title',
        'full_name',
        'position',
        'department',
        'section',
        'residence_address',
        'residence_floor',
        'number_of_residents',
        'send_status',
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
}
