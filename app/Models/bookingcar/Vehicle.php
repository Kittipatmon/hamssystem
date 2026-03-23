<?php

namespace App\Models\bookingcar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';
    protected $primaryKey = 'vehicle_id';

    protected $fillable = [
        'name',
        'model_name',
        'brand',
        'type',
        'year',
        'seat',
        'filling_volume',
        'filling_type',
        'desciption',
        'status',
        'status_vehicles',
        'latest_mileage',
        'last_maintenance_date',
        'images'
    ];

    public function bookings()
    {
        return $this->hasMany(BookingCar::class, 'vehicle_id', 'vehicle_id');
    }

    public function inspections()
    {
        return $this->hasMany(VehicleInspection::class, 'vehicle_id', 'vehicle_id');
    }
}
