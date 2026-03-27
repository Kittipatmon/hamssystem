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

        return view('backend.bookingcar.dashboard', compact(
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
        $vehicles = Vehicle::with('inspections')->orderBy('created_at', 'desc')->get();
        $bookings = BookingCar::with(['user.department', 'vehicle'])->orderBy('created_at', 'desc')->get();
        $inspections = VehicleInspection::with('vehicle')->orderBy('created_at', 'desc')->get();

        return view('backend.bookingcar.table', compact('vehicles', 'bookings', 'inspections'));
    }


    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'model_name' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'year' => 'nullable|string|max:4',
            'seat' => 'nullable|integer',
            'filling_type' => 'nullable|string|max:100',
            'latest_mileage' => 'nullable|numeric',
            'desciption' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'filling_volume' => 'nullable|string|max:20',
            'status_vehicles' => 'nullable|integer',
        ]);

        $vehicle = new Vehicle();
        $vehicle->name = $request->name;
        $vehicle->brand = $request->brand;
        $vehicle->model_name = $request->model_name;
        $vehicle->type = $request->type;
        $vehicle->year = $request->year ?? '';
        $vehicle->seat = $request->seat;
        $vehicle->filling_type = $request->filling_type;
        $vehicle->filling_volume = $request->filling_volume;
        $vehicle->status_vehicles = $request->status_vehicles ?? 1; // Default to general car
        $vehicle->latest_mileage = $request->latest_mileage ?? 0;
        $vehicle->desciption = $request->desciption;
        $vehicle->status = 'available'; // Default status

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $path = public_path('images/vehicle');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $file->move($path, $filename);
            $vehicle->images = json_encode([$filename]);
        }

        $vehicle->save();

        return redirect()->back()->with('success', 'เพิ่มรถ ' . $vehicle->name . ' เรียบร้อยแล้ว');
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->syncMileage(); // Sync latest mileage from bookings/inspections before edit
        return view('backend.bookingcar.edit_vehicle', compact('vehicle'));
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
            'year' => 'nullable|string|max:4',
            'seat' => 'nullable|integer',
            'filling_type' => 'nullable|string|max:100',
            'latest_mileage' => 'nullable|numeric',
            'desciption' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'filling_volume' => 'nullable|string|max:20',
            'status_vehicles' => 'nullable|integer',
        ]);

        $vehicle->name = $request->name;
        $vehicle->brand = $request->brand;
        $vehicle->model_name = $request->model_name;
        $vehicle->type = $request->type;
        $vehicle->year = $request->year ?? '';
        $vehicle->seat = $request->seat;
        $vehicle->filling_type = $request->filling_type;
        $vehicle->filling_volume = $request->filling_volume;
        $vehicle->status_vehicles = $request->status_vehicles ?? $vehicle->status_vehicles;
        $vehicle->latest_mileage = $request->latest_mileage ?? $vehicle->latest_mileage;
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

        return redirect()->route('backend.bookingcar.table')
            ->with('success', 'อัปเดตข้อมูลรถ ' . $vehicle->name . ' เรียบร้อยแล้ว');
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        // Optional: Check if vehicle has bookings before deleting
        if ($vehicle->bookings()->exists()) {
            return redirect()->back()->with('error', 'ไม่สามารถลบรถคันนี้ได้เนื่องจากมีข้อมูลการจองผูกติดอยู่');
        }

        $vehicle->delete();

        return redirect()->back()->with('success', 'ลบข้อมูลรถเรียบร้อยแล้ว');
    }

    /**
     * Store a new vehicle inspection.
     */
    public function storeInspection(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,vehicle_id',
            'inspection_date' => 'required|date',
            'mileage' => 'required|numeric',
            'next_mileage' => 'nullable|numeric',
            'inspector_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'file_vehicle' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480',
            'description' => 'nullable|string',
            'status' => 'required|integer'
        ]);

        $inspection = new VehicleInspection();
        $inspection->vehicle_id = $request->vehicle_id;
        $inspection->inspection_date = $request->inspection_date;
        $inspection->mileage = $request->mileage;
        $inspection->next_mileage = $request->next_mileage;
        $inspection->inspector_name = $request->inspector_name;
        $inspection->location = $request->location;
        $inspection->description = $request->description;
        $inspection->status = $request->status;

        if ($request->hasFile('file_vehicle')) {
            $file = $request->file('file_vehicle');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('uploads/vehicl_file_maintenance');
            
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            
            $file->move($path, $filename);
            $inspection->file_vehicle = $filename;
        }

        $inspection->save();

        // Sync vehicle latest mileage
        $vehicle = Vehicle::findOrFail($request->vehicle_id);
        $this->syncVehicleMileage($vehicle);

        return redirect()->back()->with('success', 'บันทึกข้อมูลการตรวจเช็คเรียบร้อยแล้ว');
    }

    /**
     * Update an existing vehicle inspection.
     */
    public function updateInspection(Request $request, $id)
    {
        $request->validate([
            'inspection_date' => 'required|date',
            'mileage' => 'required|numeric',
            'next_mileage' => 'nullable|numeric',
            'inspector_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'file_vehicle' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:20480',
            'description' => 'nullable|string',
            'status' => 'required|integer'
        ]);

        $inspection = VehicleInspection::findOrFail($id);
        $inspection->inspection_date = $request->inspection_date;
        $inspection->mileage = $request->mileage;
        $inspection->next_mileage = $request->next_mileage;
        $inspection->inspector_name = $request->inspector_name;
        $inspection->location = $request->location;
        $inspection->description = $request->description;
        $inspection->status = $request->status;

        if ($request->hasFile('file_vehicle')) {
            $file = $request->file('file_vehicle');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('uploads/vehicl_file_maintenance');
            
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            
            // Optional: delete old file
            if ($inspection->file_vehicle && file_exists($path . '/' . $inspection->file_vehicle)) {
                @unlink($path . '/' . $inspection->file_vehicle);
            }
            
            $file->move($path, $filename);
            $inspection->file_vehicle = $filename;
        }

        $inspection->save();

        // Sync vehicle latest mileage
        $this->syncVehicleMileage($inspection->vehicle);

        return redirect()->back()->with('success', 'อัปเดตข้อมูลการตรวจเช็คเรียบร้อยแล้ว');
    }

    /**
     * Remove an existing vehicle inspection.
     */
    public function destroyInspection($id)
    {
        $inspection = VehicleInspection::findOrFail($id);
        $vehicle = $inspection->vehicle;
        
        // Delete file if exists
        if ($inspection->file_vehicle) {
            $path = public_path('uploads/vehicl_file_maintenance/' . $inspection->file_vehicle);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
        
        $inspection->delete();

        // Recalculate latest mileage for vehicle
        $this->syncVehicleMileage($vehicle);

        return redirect()->back()->with('success', 'ลบข้อมูลการตรวจเช็คเรียบร้อยแล้ว');
    }

    /**
     * Synchronize the vehicle's latest mileage and maintenance date from inspections.
     */
    private function syncVehicleMileage(Vehicle $vehicle)
    {
        $vehicle->syncMileage();
    }
}
