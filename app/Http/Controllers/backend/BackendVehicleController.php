<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bookingcar\Vehicle;
use App\Models\bookingcar\BookingCar;
use App\Models\bookingcar\VehicleInspection;
use Illuminate\Support\Facades\Auth;

class BackendVehicleController extends Controller
{
    /**
     * Dashboard view showing KPI statistics
     */
    public function dashboard()
    {
        // 1. Total Vehicles
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'available')->count();

        // 2. Booking stats
        $totalBookings = BookingCar::count();
        $pendingBookings = BookingCar::where('status', 'รออนุมัติ')->count();
        $approvedBookings = BookingCar::where('status', 'อนุมัติแล้ว')->count();

        // 3. Inspection stats
        $totalInspections = VehicleInspection::count();
        $pendingInspections = VehicleInspection::where('status', 1)->count();

        // Optional: recent bookings for snapshot
        $recentBookings = BookingCar::with(['user', 'vehicle'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('backend.vehicles.dashboard', compact(
            'totalVehicles',
            'availableVehicles',
            'totalBookings',
            'pendingBookings',
            'approvedBookings',
            'totalInspections',
            'pendingInspections',
            'recentBookings'
        ));
    }

    /**
     * Data table view showing detailed records
     */
    public function table()
    {
        // Get data for tables, maybe apply pagination
        $vehicles = Vehicle::orderBy('created_at', 'desc')->get();
        $bookings = BookingCar::with(['user', 'vehicle'])->orderBy('created_at', 'desc')->get();
        $inspections = VehicleInspection::with('vehicle')->orderBy('created_at', 'desc')->get();

        return view('backend.bookingcar.table', compact('vehicles', 'bookings', 'inspections'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return view('backend.vehicles.edit_vehicle', compact('vehicle'));
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'model_name' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'seat' => 'nullable|integer',
            'filling_type' => 'nullable|string|max:100',
            'desciption' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $vehicle->name = $request->name;
        $vehicle->brand = $request->brand;
        $vehicle->model_name = $request->model_name;
        $vehicle->type = $request->type;
        $vehicle->seat = $request->seat;
        $vehicle->filling_type = $request->filling_type;
        $vehicle->desciption = $request->desciption;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Ensure directory exists
            $path = public_path('images/vehicle');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $file->move($path, $filename);

            // Store as JSON array as per existing view logic
            $vehicle->images = json_encode([$filename]);
        }

        $vehicle->save();

        return redirect()->route('backend.vehicles.table')
            ->with('success', 'อัปเดตข้อมูลรถ ' . $vehicle->name . ' เรียบร้อยแล้ว');
    }
}
