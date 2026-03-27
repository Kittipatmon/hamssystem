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

    /**
     * Get the latest inspection record
     */
    public function latestInspection()
    {
        return $this->inspections()->orderBy('inspection_date', 'desc')->orderBy('inspection_id', 'desc')->first();
    }

    /**
     * Get the mileage baseline for maintenance progress
     */
    public function getLastMaintenanceMileage()
    {
        $latest = $this->latestInspection();
        return $latest ? (int)$latest->mileage : 0;
    }

    /**
     * Get the next mileage goal for maintenance
     */
    public function getNextMaintenanceMileage()
    {
        $latest = $this->latestInspection();
        return $latest ? (int)($latest->next_mileage ?? ($latest->mileage + 10000)) : 10000;
    }

    /**
     * Synchronize the vehicle's latest mileage from all sources (Inspections & Bookings)
     */
    public function syncMileage()
    {
        $maxInspection = $this->inspections()->max('mileage') ?? 0;
        $maxBooking = $this->bookings()->where('return_status', 'ส่งคืนแล้ว')->max('mileage_after') ?? 0;
        
        $this->latest_mileage = max($this->latest_mileage, $maxInspection, $maxBooking);
        
        $latestInsp = $this->latestInspection();
        if ($latestInsp) {
            $this->last_maintenance_date = $latestInsp->inspection_date;
        }
        
        $this->save();
    }
}
