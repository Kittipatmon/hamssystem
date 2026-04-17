<?php

namespace App\Http\Controllers\serviceshams;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\serviceshams\Requisitions;
use App\Models\serviceshams\Requisition_items;
use App\Models\serviceshams\Items;
use Illuminate\Support\Facades\Auth;
// Removed PDF facade import as export PDF feature is deprecated
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

use App\Models\Department;
use App\Models\Division;
use App\Models\Section;
use App\Models\User;

class RequisitionsController extends Controller
{
    public function welcomeService()
    {
        // HAMS-only stats panel data
        $pendingApproveCount = Requisitions::where('approve_status', Requisitions::APPROVE_STATUS_PENDING)
            ->where('status', Requisitions::STATUS_PENDING)
            ->count();
        $updatedCount = Requisitions::where('approve_status', '!=', Requisitions::APPROVE_STATUS_PENDING)
            ->where('status', Requisitions::STATUS_PENDING)
            ->count();
        $allReqCount = Requisitions::count();

        $checklistPendingCount = Requisitions::where('packing_staff_status', Requisitions::PACKING_STATUS_PENDING)
            ->where('status', Requisitions::STATUS_PENDING)
            ->count();
        $packingDoneCount = Requisitions::where('packing_staff_status', '!=', Requisitions::PACKING_STATUS_PENDING)
            ->count();

        $statsCount = Requisitions::where('status', Requisitions::STATUS_END_PROGRESS)->count();
        $reportsAllCount = Requisitions::count();

        return view('serviceshams.welcomeservice', compact(
            'pendingApproveCount',
            'updatedCount',
            'allReqCount',
            'checklistPendingCount',
            'packingDoneCount',
            'statsCount',
            'reportsAllCount'
        ));
    }

    public function ReqlistPending()
    {
        $userId = Auth::id();
        $requisitions = Requisitions::where(function ($query) use ($userId) {
            // 1. My own requests
            $query->where('requester_id', $userId)
                ->where(function ($q) {
                    $q->whereIn('status', [Requisitions::STATUS_PENDING, Requisitions::STATUS_APPROVED])
                        ->orWhere(function ($sq) {
                            $sq->where('status', Requisitions::STATUS_END_PROGRESS)
                                ->where('updated_at', '>=', now()->subHours(24));
                        });
                });

            // 2. Requisitions I need to approve (Pending approval)
            $query->orWhere(function ($q) use ($userId) {
                $q->where('approve_id', $userId)
                    ->where('approve_status', 0) // Pending
                    ->where('status', Requisitions::STATUS_PENDING);
            });
        })
            ->orderBy('created_at', 'desc')
            ->get();
        $requisition_items = Requisition_items::with('item')->get();
        return view('serviceshams.requisitions.reqpending', compact('requisitions', 'requisition_items'));
    }

    public function ReqlistAll(Request $request)
    {
        $query = Requisitions::orderBy('created_at', 'desc');

        // Access Control: Non-HAMS/Admin only see their own OR those they need to approve
        $isHamsOrAdmin = Auth::check() && (Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16]));
        if (!$isHamsOrAdmin) {
            $query->where(function ($q) {
                $q->where('requester_id', Auth::id())
                    ->orWhere('approve_id', Auth::id());
            });
        }

        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $requisitions = $query->get();
        $requisition_items = Requisition_items::with('item')->get();

        $years = Requisitions::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $approvers = User::select('id', 'firstname', 'lastname', 'dept_id', 'role')
            ->with('department')
            ->get();

        $isHamsOrAdmin = Auth::check() && (Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16]));

        return view('serviceshams.requisitions.reqlistall', compact('requisitions', 'requisition_items', 'years', 'approvers', 'isHamsOrAdmin'));
    }

    public function updateAllApprovers(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'approve_id' => 'nullable|integer',
        ]);

        $item = Requisitions::findOrFail($request->id);

        $item->update([
            'approve_id' => $request->approve_id ?: null,
        ]);

        return response()->json(['success' => true]);
    }

    public function quickApprove(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|integer', // 1: Approved, 2: Rejected
        ]);

        $item = Requisitions::findOrFail($request->id);

        $item->update([
            'approve_status' => $request->status,
            'approve_id' => Auth::id(),
            'approve_date' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    // Removed showPDF method as export PDF feature is deprecated

    public function DetailReqPending($id)
    {
        $requisition = Requisitions::findOrFail($id);

        // Access Control
        $isOwner = $requisition->requester_id === Auth::id();
        $isHamsOrAdmin = Auth::check() && (Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16]));
        if (!$isOwner && !$isHamsOrAdmin) {
            return redirect()->route('requisitions.reqlistpending')->with('error', 'Unauthorized access.');
        }

        $requisition_items = Requisition_items::where('requisition_id', $id)->get();
        return view('serviceshams.requisitions.detailreqpedding', compact('requisition', 'requisition_items'));
    }

    public function DetailReqAlllist($id)
    {
        $requisition = Requisitions::findOrFail($id);

        // Access Control
        $isOwner = $requisition->requester_id === Auth::id();
        $isHamsOrAdmin = Auth::check() && (Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16]));
        if (!$isOwner && !$isHamsOrAdmin) {
            return redirect()->route('requisitions.reqlistall')->with('error', 'Unauthorized access.');
        }

        $requisition_items = Requisition_items::where('requisition_id', $id)->get();
        return view('serviceshams.requisitions.detailreqlistall', compact('requisition', 'requisition_items'));
    }

    public function DetailExportPdf($id)
    {
        $requisition = Requisitions::with(['user', 'requisition_items.item'])->findOrFail($id);

        // Access Control
        $isOwner = $requisition->requester_id === Auth::id();
        $isHamsOrAdmin = Auth::check() && (Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16]));
        if (!$isOwner && !$isHamsOrAdmin) {
            return redirect()->route('requisitions.reqlistall')->with('error', 'Unauthorized access.');
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('serviceshams.requisitions.requisition_detail_pdf', compact('requisition'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'requisition_' . ($requisition->requisitions_code ?? $id) . '.pdf';
        return $pdf->download($filename);
    }


    public function reqChecklist()
    {
        $requisitions = Requisitions::where('packing_staff_status', Requisitions::PACKING_STATUS_PENDING)
            ->where('status', Requisitions::STATUS_PENDING)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('serviceshams.requisitions.reqchecklist', compact('requisitions'));
    }

    public function DetailChecklist($id)
    {
        $requisition = Requisitions::findOrFail($id);
        $requisition_items = Requisition_items::where('requisition_id', $id)->get();
        return view('serviceshams.requisitions.detailchecklist', compact('requisition', 'requisition_items'));
    }


    public function cancel(Request $request, $id)
    {
        $requisition = Requisitions::findOrFail($id);
        $requisition->status = Requisitions::STATUS_CANCELLED;
        $requisition->requester_comment = $request->input('requester_comment');
        $requisition->save();

        $requisition_items = Requisition_items::where('requisition_id', $id)->get();
        foreach ($requisition_items as $item) {
            $itemModel = Items::findOrFail($item->item_id);
            $itemModel->quantity += $item->quantity; // เพิ่มจำนวนสินค้าคืน
            // $itemModel->items_per_pack += $item->quantity_pack; // เพิ่มจำนวนสินค้าคืน
            $itemModel->save();
        }
        // $requisition->requisition_items()->delete(); // ลบรายการเบิกรายการย่อย

        return redirect()->route('requisitions.reqlistpending')->with('success', 'คำขอยกเลิกรายการ สำเร็จเรียบร้อยแล้ว.');
    }


    public function dashboardRequisition()
    {
        // Lightweight initial data for SSR; heavy aggregations done once here then AJAX for filters.
        $currentYear = date('Y');
        $requisitions = Requisitions::whereYear('created_at', $currentYear)
            ->select('requisitions_id', 'status', 'created_at', 'total_price')
            ->get();
        $requisition_id_list = $requisitions->pluck('requisitions_id');
        $requisition_items = Requisition_items::with(['item:item_id,name'])
            ->whereIn('requisition_id', $requisition_id_list)
            ->select('requistionitem_id', 'requisition_id', 'item_id', 'quantity')
            ->get();

        $totalRequisitions = $requisitions->count();
        $pendingRequisitions = $requisitions->where('status', Requisitions::STATUS_PENDING)->count();
        $approvedRequisitions = $requisitions->where('status', Requisitions::STATUS_END_PROGRESS)->count();
        $cancelledRequisitions = $requisitions->where('status', Requisitions::STATUS_CANCELLED)->count();
        $rejectedRequisitions = $requisitions->where('status', Requisitions::STATUS_REJECTED)->count();

        // Aggregate monthly stats & item totals (same shape as AJAX response)
        $monthlyStats = [];
        $itemTotals = [];
        $monthlyExpenseTotals = [];
        $monthlyRequisitionCounts = []; // counts of requisition records per month grouped by status
        foreach ($requisition_items as $ri) {
            $req = $requisitions->firstWhere('requisitions_id', $ri->requisition_id);
            if (!$req) {
                continue;
            }
            $monthKey = $req->created_at ? Carbon::parse($req->created_at)->format('Y-m') : 'unknown';
            $name = $ri->item->name ?? 'ไม่ทราบชื่อ';
            $monthlyStats[$monthKey][$name] = ($monthlyStats[$monthKey][$name] ?? 0) + $ri->quantity;
            $itemTotals[$name] = ($itemTotals[$name] ?? 0) + $ri->quantity;
            $monthlyExpenseTotals[$monthKey] = ($monthlyExpenseTotals[$monthKey] ?? 0) + (float) ($req->total_price ?? 0);
            // We will compute requisition counts separately below to avoid double counting inside item loop.
        }

        // Aggregate requisition counts per month by status (each requisition counted once)
        foreach ($requisitions as $req) {
            $monthKey = $req->created_at ? Carbon::parse($req->created_at)->format('Y-m') : 'unknown';
            $status = $req->status ?? 'unknown';
            $monthlyRequisitionCounts[$monthKey][$status] = ($monthlyRequisitionCounts[$monthKey][$status] ?? 0) + 1;
        }

        $departments = Department::all(['id', 'name']);

        $years = Requisitions::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('serviceshams.reports.dashboard', compact(
            'totalRequisitions',
            'pendingRequisitions',
            'approvedRequisitions',
            'rejectedRequisitions',
            'cancelledRequisitions',
            'monthlyStats',
            'itemTotals',
            'monthlyExpenseTotals',
            'monthlyRequisitionCounts',
            'departments',
            'years'
        ));
    }

    /**
     * JSON data endpoint for advanced dashboard with real-time filters.
     * Filters: date_from, date_to (YYYY-MM-DD), section, division, department
     */
    public function dashboardData(Request $request)
    {
        // Base query
        $reqQuery = Requisitions::query()->select('requisitions_id', 'status', 'created_at', 'total_price', 'requester_id')
            ->with(['user:id,dept_id']);

        if ($request->filled('year')) {
            $reqQuery->whereYear('created_at', $request->year);
        }

        $filteredRequisitions = $reqQuery->get();

        // Apply structural filters based on requester (user) attributes instead of requisitions columns.
        // NOTE: User model uses a separate connection; we perform in-memory filtering to avoid cross-connection joins.
        // Structural filtering for section/division is currently disconnected as these fields were removed in migration.
        // Keeping logic for department only.
        if ($request->filled('department')) {
            $departmentVal = $request->input('department');
            $filteredRequisitions = $filteredRequisitions->filter(fn($r) => optional($r->user)->dept_id == $departmentVal);
        }

        if ($request->filled('search')) {
            $kw = strtolower($request->input('search'));
            $filteredRequisitions = $filteredRequisitions->filter(function ($r) use ($kw) {
                $user = optional($r->user);
                return str_contains(strtolower($user->fullname ?? ''), $kw) ||
                    str_contains(strtolower($user->emp_code ?? ''), $kw) ||
                    str_contains(strtolower($r->requisitions_code ?? ''), $kw);
            });
        }

        // Rebuild IDs after structural filtering.
        $ids = $filteredRequisitions->pluck('requisitions_id');

        $itemsQuery = DB::table('requisition_items as ri')
            ->join('requisitions as r', 'ri.requisition_id', '=', 'r.requisitions_id')
            ->leftJoin('items as i', 'ri.item_id', '=', 'i.item_id')
            ->whereIn('ri.requisition_id', $ids);
        // Date filters still applied to item aggregation.
        if ($request->filled('year')) {
            $itemsQuery->whereYear('r.created_at', $request->year);
        }

        // Monthly item usage
        $monthlyRows = $itemsQuery->clone()
            ->selectRaw("DATE_FORMAT(r.created_at, '%Y-%m') as month_key, COALESCE(i.name,'ไม่ทราบชื่อ') as item_name, SUM(ri.quantity) as qty")
            ->groupBy('month_key', 'item_name')
            ->orderBy('month_key')
            ->get();
        $monthlyStats = [];
        foreach ($monthlyRows as $row) {
            $monthlyStats[$row->month_key][$row->item_name] = (int) $row->qty;
        }

        // Top items (limit 10)
        $topItemsRows = $itemsQuery->clone()
            ->selectRaw("COALESCE(i.name,'ไม่ทราบชื่อ') as item_name, SUM(ri.quantity) as qty")
            ->groupBy('item_name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();
        $topItems = $topItemsRows->map(fn($r) => ['name' => $r->item_name, 'quantity' => (int) $r->qty]);

        // Monthly totals (sum of all items each month)
        $monthlyTotals = [];
        foreach ($monthlyStats as $m => $items) {
            $monthlyTotals[$m] = array_sum($items);
        }

        // Monthly expense totals (sum of requisition total_price per month)
        $monthlyExpenseTotals = [];
        foreach ($filteredRequisitions as $req) {
            $monthKey = $req->created_at ? Carbon::parse($req->created_at)->format('Y-m') : 'unknown';
            $monthlyExpenseTotals[$monthKey] = ($monthlyExpenseTotals[$monthKey] ?? 0) + (float) ($req->total_price ?? 0);
        }

        // Monthly requisition counts per status
        $monthlyRequisitionCounts = [];
        foreach ($filteredRequisitions as $req) {
            $monthKey = $req->created_at ? Carbon::parse($req->created_at)->format('Y-m') : 'unknown';
            $status = $req->status ?? 'unknown';
            $monthlyRequisitionCounts[$monthKey][$status] = ($monthlyRequisitionCounts[$monthKey][$status] ?? 0) + 1;
        }

        $summary = [
            'total' => $filteredRequisitions->count(),
            'pending' => $filteredRequisitions->where('status', Requisitions::STATUS_PENDING)->count(),
            'approved' => $filteredRequisitions->where('status', Requisitions::STATUS_END_PROGRESS)->count(),
            'cancelled' => $filteredRequisitions->where('status', Requisitions::STATUS_CANCELLED)->count(),
            'rejected' => $filteredRequisitions->where('status', Requisitions::STATUS_REJECTED)->count(),
        ];

        return response()->json([
            'summary' => $summary,
            'monthly_stats' => $monthlyStats,
            'monthly_totals' => $monthlyTotals,
            'top_items' => $topItems,
            'monthly_expense_totals' => $monthlyExpenseTotals,
            'monthly_requisition_counts' => $monthlyRequisitionCounts,
        ]);
    }

    // Removed dashboardExportPdf method as export PDF feature is deprecated

    public function Reportslistall(Request $request)
    {
        $query = Requisitions::query()
            ->with(['user.department', 'requisition_items'])
            ->orderBy('created_at', 'desc');

        // Access Control: Non-HAMS/Admin only see their own
        $isHamsOrAdmin = Auth::check() && (Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16]));
        if (!$isHamsOrAdmin) {
            $query->where('requester_id', Auth::user()->id);
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', Carbon::parse($request->input('start_date'))->startOfDay());
        }
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', Carbon::parse($request->input('end_date'))->endOfDay());
        }

        $requisitions = $query->get();
        $requisition_items = Requisition_items::with('item')->get();

        return view('serviceshams.reports.reportslistall', compact('requisitions', 'requisition_items'));
    }

    public function ReportslistallExportPdf(Request $request)
    {
        $query = Requisitions::query()
            ->with(['user.department', 'requisition_items'])
            ->orderBy('created_at', 'desc');

        // Access Control: Non-HAMS/Admin only see their own
        $isHamsOrAdmin = Auth::check() && (Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16]));
        if (!$isHamsOrAdmin) {
            $query->where('requester_id', Auth::user()->id);
        }

        $df = $request->input('date_from') ?: $request->input('start_date');
        $dt = $request->input('date_to') ?: $request->input('end_date');

        if ($df) {
            $query->where('created_at', '>=', Carbon::parse($df)->startOfDay());
        }
        if ($dt) {
            $query->where('created_at', '<=', Carbon::parse($dt)->endOfDay());
        }
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $requisitions = $query->get();

        // Structural Filter: Department (in-memory due to cross-connection restrictions)
        if ($request->filled('department')) {
            $deptId = $request->input('department');
            $requisitions = $requisitions->filter(fn($r) => optional($r->user)->dept_id == $deptId);
        }

        // Render a dedicated PDF view
        $html = view('serviceshams.reports.reportslistall_pdf', [
            'requisitions' => $requisitions,
            'start_date' => $df,
            'end_date' => $dt,
        ])->render();

        // Use Dompdf via the barryvdh facade
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);
        $pdf->setPaper('A4', 'landscape');

        $filename = 'requisitions_report_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }

    public function ReportslistallExportCsv(Request $request)
    {
        $query = Requisitions::query()
            ->with(['user.department', 'requisition_items'])
            ->orderBy('created_at', 'desc');

        // Access Control: Non-HAMS/Admin only see their own
        $isHamsOrAdmin = Auth::check() && (Auth::user()->role === 'admin' || in_array(Auth::user()->dept_id, [14, 16]));
        if (!$isHamsOrAdmin) {
            $query->where('requester_id', Auth::user()->id);
        }

        $df = $request->input('date_from') ?: $request->input('start_date');
        $dt = $request->input('date_to') ?: $request->input('end_date');

        if ($df) {
            $query->where('created_at', '>=', Carbon::parse($df)->startOfDay());
        }
        if ($dt) {
            $query->where('created_at', '<=', Carbon::parse($dt)->endOfDay());
        }
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $requisitions = $query->get();

        // Structural Filter: Department
        if ($request->filled('department')) {
            $deptId = $request->input('department');
            $requisitions = $requisitions->filter(fn($r) => optional($r->user)->dept_id == $deptId);
        }

        $filename = 'requisitions_report_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = [
            'เลขที่คำขอ',
            'ผู้ขอ',
            'สายงาน',
            'ฝ่าย',
            'แผนก',
            'วันที่ขอ',
            'จำนวนรายการ',
            'ราคารวม(บาท)',
            'สถานะจัดส่ง',
            'สถานะคำขอ',
        ];

        $callback = function () use ($requisitions, $columns) {
            $output = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility with Thai text
            fprintf($output, "\xEF\xBB\xBF");
            fputcsv($output, $columns);

            foreach ($requisitions as $req) {
                $row = [
                    $req->requisitions_code ?? '-',
                    optional($req->user)->fullname ? ('คุณ' . $req->user->fullname) : '-',
                    optional(optional($req->user)->section)->section_code ?? '-',
                    optional(optional($req->user)->division)->division_name ?? '-',
                    optional(optional($req->user)->department)->department_name ?? '-',
                    optional($req->request_date)->format('d/m/Y') ?? '-',
                    $req->requisition_items->count() ?? 0,
                    number_format((float) ($req->total_price ?? 0), 2),
                    $req->packing_status_label ?? '—',
                    $req->status_label ?? '—',
                ];
                fputcsv($output, $row);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }

}

