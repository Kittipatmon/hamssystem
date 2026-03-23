<?php

namespace App\Models\bookingcar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleInspection extends Model
{
    use HasFactory;

    protected $table = 'vehicle_inspections';
    protected $primaryKey = 'inspection_id';

    protected $fillable = [
        'vehicle_id',
        'inspection_date',
        'mileage',
        'inspector_name',
        'location',
        'district',
        'province',
        'description',
        'next_mileage',
        'next_maintenance_date',
        'file_vehicle',
        'status'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }
}
