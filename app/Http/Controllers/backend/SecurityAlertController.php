<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Carbon\Carbon;

class SecurityAlertController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action. Only Admin can access Security Alerts.');
        }

        $query = Activity::where('log_name', 'login_failed')->latest();

        // Optional filtering by IP or Email/Username
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('properties->ip', 'like', "%{$search}%")
                  ->orWhere('properties->email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('risk_level')) {
            $isRisk = $request->risk_level === 'high';
            $query->where('properties->risk', $isRisk);
        }

        // Month/Year filter
        $filterMonth = $request->input('filter_month');
        $filterYear = $request->input('filter_year');
        if ($filterYear && $filterMonth) {
            $filterStart = Carbon::createFromDate($filterYear, $filterMonth, 1)->startOfMonth();
            $filterEnd = $filterStart->copy()->endOfMonth();
            $query->whereBetween('created_at', [$filterStart, $filterEnd]);
        } elseif ($filterYear) {
            $filterStart = Carbon::createFromDate($filterYear, 1, 1)->startOfYear();
            $filterEnd = $filterStart->copy()->endOfYear();
            $query->whereBetween('created_at', [$filterStart, $filterEnd]);
        } elseif ($filterMonth) {
            $query->whereMonth('created_at', $filterMonth);
        }

        // Build available years for dropdown (3 years back + any years in DB)
        $currentYear = Carbon::now()->year;
        $baseYears = range($currentYear, $currentYear - 2);
        
        $dbYears = Activity::where('log_name', 'login_failed')
            ->selectRaw("DISTINCT YEAR(created_at) as y")
            ->pluck('y')
            ->toArray();
            
        $availableYears = array_unique(array_merge($baseYears, $dbYears));
        rsort($availableYears);

        // Stats for dashboard
        $todayStarts = Carbon::today();
        
        $totalFailsToday = Activity::where('log_name', 'login_failed')
            ->where('created_at', '>=', $todayStarts)
            ->count();
            
        $highRiskToday = Activity::where('log_name', 'login_failed')
            ->where('created_at', '>=', $todayStarts)
            ->where('properties->risk', true)
            ->count();

        // Get logs for the table
        $logs = $query->limit(500)->get();

        // Group by email/username
        $groupedLogs = $logs->groupBy(function($item) {
            return $item->properties['email'] ?? 'Unknown';
        })->map(function($group, $email) {
            $latest = $group->first();
            
            $totalAttempts = $group->max(function($item) {
                return $item->properties['attempts'] ?? 1;
            });
            
            $isHighRisk = $group->contains(function($item) {
                return isset($item->properties['risk']) && $item->properties['risk'] == true;
            });
            
            $ips = $group->map(function($item) {
                return $item->properties['ip'] ?? 'Unknown';
            })->unique()->values()->toArray();
            
            $history = $group->map(function($item) {
                return [
                    'date' => $item->created_at->format('d/m/Y, H:i:s'),
                    'ip' => $item->properties['ip'] ?? 'Unknown',
                    'attempts' => $item->properties['attempts'] ?? 1,
                    'risk' => isset($item->properties['risk']) && $item->properties['risk'] == true ? 'High' : 'Low',
                    'description' => $item->description
                ];
            })->toArray();

            return (object) [
                'email' => $email,
                'latest_date' => $latest->created_at->format('d/m/Y, H:i:s'),
                'ips' => $ips,
                'total_attempts' => $totalAttempts,
                'risk' => $isHighRisk,
                'description' => $latest->description,
                'history' => $history,
                'latest_ip' => $latest->properties['ip'] ?? 'Unknown'
            ];
        })->values();

        // Get all banned IPs to pass to view
        $bannedIps = \App\Models\IpBlacklist::pluck('ip_address')->toArray();

        // ===== Analytics Data for Charts =====

        // Monthly stats - last 12 months
        $monthlyStats = Activity::where('log_name', 'login_failed')
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Monthly risk breakdown - last 12 months
        $monthlyHighRisk = Activity::where('log_name', 'login_failed')
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->where('properties->risk', true)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Build 12-month labels and data arrays
        $monthLabels = [];
        $monthTotals = [];
        $monthHighRisk = [];
        $monthLowRisk = [];
        for ($i = 11; $i >= 0; $i--) {
            $key = Carbon::now()->subMonths($i)->format('Y-m');
            $label = Carbon::now()->subMonths($i)->locale('th')->translatedFormat('M Y');
            $monthLabels[] = $label;
            $total = $monthlyStats[$key] ?? 0;
            $high = $monthlyHighRisk[$key] ?? 0;
            $monthTotals[] = $total;
            $monthHighRisk[] = $high;
            $monthLowRisk[] = max(0, $total - $high);
        }

        // Top 10 attacking IPs (all time)
        $topIps = Activity::where('log_name', 'login_failed')
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(properties, '$.ip')) as ip_address, COUNT(*) as total")
            ->groupBy('ip_address')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'ip' => $item->ip_address ?? 'Unknown',
                    'total' => $item->total
                ];
            });

        // Banned IP count
        $bannedIpCount = \App\Models\IpBlacklist::count();

        // Unique IPs this month
        $uniqueIpsThisMonth = Activity::where('log_name', 'login_failed')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->selectRaw("COUNT(DISTINCT JSON_UNQUOTE(JSON_EXTRACT(properties, '$.ip'))) as cnt")
            ->value('cnt') ?? 0;

        // Trend: this month vs last month
        $thisMonthCount = Activity::where('log_name', 'login_failed')
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
        $lastMonthCount = Activity::where('log_name', 'login_failed')
            ->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->startOfMonth()])
            ->count();
        $trendPercentage = $lastMonthCount > 0 
            ? round((($thisMonthCount - $lastMonthCount) / $lastMonthCount) * 100, 1) 
            : ($thisMonthCount > 0 ? 100 : 0);

        // All-time stats
        $totalAllTime = Activity::where('log_name', 'login_failed')->count();
        $firstAlertDate = Activity::where('log_name', 'login_failed')
            ->oldest()
            ->value('created_at');
        $firstAlertDate = $firstAlertDate ? Carbon::parse($firstAlertDate)->format('d/m/Y') : '-';

        return view('backend.security-alerts.index', compact(
            'groupedLogs', 'totalFailsToday', 'highRiskToday', 'bannedIps',
            'monthLabels', 'monthTotals', 'monthHighRisk', 'monthLowRisk',
            'topIps', 'bannedIpCount', 'uniqueIpsThisMonth',
            'trendPercentage', 'thisMonthCount', 'lastMonthCount',
            'totalAllTime', 'firstAlertDate',
            'availableYears', 'filterMonth', 'filterYear'
        ));
    }

    public function toggleBanIp(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'ip' => 'required|ip',
            'is_banned' => 'required|boolean'
        ]);

        $ip = $request->ip;

        if ($ip === request()->ip()) {
            return response()->json(['success' => false, 'message' => 'ไม่สามารถบล็อก IP ของตัวคุณเองได้'], 400);
        }

        if ($request->is_banned) {
            \App\Models\IpBlacklist::firstOrCreate(
                ['ip_address' => $ip],
                [
                    'reason' => 'Banned from Security Alerts dashboard',
                    'banned_by' => auth()->id()
                ]
            );
            $message = "IP {$ip} ถูกบล็อกเรียบร้อยแล้ว";
        } else {
            \App\Models\IpBlacklist::where('ip_address', $ip)->delete();
            $message = "ปลดบล็อก IP {$ip} เรียบร้อยแล้ว";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
