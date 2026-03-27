<?php

namespace App\Models\housing;

use Illuminate\Database\Eloquent\Model;

class ResidenceDependent extends Model
{
    protected $table = 'residence_dependents';
    protected $primaryKey = 'dependents_id';

    protected $fillable = [
        'request_id', 'full_name', 'relation', 'age', 'related_detail'
    ];

    public function request()
    {
        return $this->belongsTo(ResidenceRequest::class, 'request_id', 'id');
    }
}
