<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class SystemLogController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only Admin can access System Logs.');
        }

        $query = Activity::with(['causer', 'subject'])->latest();

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        $logs = $query->limit(1000)->get();

        $logTypes = \Spatie\Activitylog\Models\Activity::select('log_name')->distinct()->pluck('log_name');

        return view('backend.system-logs.index', compact('logs', 'logTypes'));
    }

    public function archives()
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        $archivePath = storage_path('app/activity-logs-archive');
        $archives = [];
        if (is_dir($archivePath)) {
            foreach (glob($archivePath . '/*.zip') as $file) {
                $archives[] = [
                    'name' => basename($file),
                    'size' => round(filesize($file) / 1024 / 1024, 2), // MB
                    'date' => date('Y-m-d H:i:s', filemtime($file))
                ];
            }
        }
        
        // Sort newest first
        usort($archives, function($a, $b) {
            return $b['name'] <=> $a['name'];
        });

        return response()->json($archives);
    }

    public function downloadArchive($filename)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only Admin can access System Logs.');
        }
        $file = storage_path('app/activity-logs-archive/' . basename($filename));
        if (file_exists($file)) {
            return response()->download($file);
        }
        abort(404, 'Archive not found');
    }
}
