<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงานรายการเบิกของทั้งหมด</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin: 0 0 10px 0; }
        .meta { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        th { background: #f1f5f9; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>รายงานรายการเบิกของทั้งหมด</h2>
    <div class="meta">
        ช่วงวันที่:
        {{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d/m/Y') : '-' }}
        ถึง
        {{ $end_date ? \Carbon\Carbon::parse($end_date)->format('d/m/Y') : '-' }}
    </div>
    <table>
        <thead>
            <tr>
                <th>เลขที่คำขอ</th>
                <th>ผู้ขอ</th>
                <th>สายงาน</th>
                <th>ฝ่าย</th>
                <th>แผนก</th>
                <th>วันที่ขอ</th>
                <th>จำนวนรายการ</th>
                <th class="text-right">ราคารวม(บาท)</th>
                <th>สถานะจัดส่ง</th>
                <th>สถานะคำขอ</th>
            </tr>
        </thead>
        <tbody>
        @forelse($requisitions as $req)
            <tr>
                <td>{{ $req->requisitions_code ?? '-' }}</td>
                <td>{{ optional($req->user)->fullname ? ('คุณ' . $req->user->fullname) : '-' }}</td>
                <td>{{ optional(optional($req->user)->section)->section_code ?? '-' }}</td>
                <td>{{ optional(optional($req->user)->division)->division_name ?? '-' }}</td>
                <td>{{ optional(optional($req->user)->department)->department_name ?? '-' }}</td>
                <td>{{ optional($req->request_date)->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $req->requisition_items->count() ?? 0 }}</td>
                <td class="text-right">{{ number_format((float) ($req->total_price ?? 0), 2) }}</td>
                <td>{{ $req->packing_status_label ?? '—' }}</td>
                <td>{{ $req->status_label ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="10" style="text-align:center;">ไม่พบข้อมูลคำขอ</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
