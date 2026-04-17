<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>HAMS — รายงานรายการเบิกอุปกรณ์</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            src: url('{{ public_path('fonts/THSarabunNew.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'THSarabunNew';
            src: url('{{ public_path('fonts/THSarabunNew-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        body { 
            font-family: "THSarabunNew", sans-serif; 
            font-size: 16px; 
            line-height: 1.2;
            color: #1e293b;
        }
        
        @page {
            margin: 1cm;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            color: #dc2626;
            margin: 0;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #64748b;
            font-size: 14px;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .meta-table td {
            border: none;
            padding: 2px 0;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }

        th, td { 
            border: 1px solid #e2e8f0; 
            padding: 8px 6px; 
            text-align: left;
        }

        th { 
            background: #f8fafc; 
            font-weight: bold;
            color: #475569;
            font-size: 14px;
            text-transform: uppercase;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .status-badge {
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            display: inline-block;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>รายงานรายการเบิกพัสดุอุปกรณ์สำนักงาน</h1>
        <p>Human Asset Management & Service Building (HAMS)</p>
    </div>

    <table class="meta-table">
        <tr>
            <td width="50%">
                @if(request('year'))
                    <span class="font-bold">รายงานประจำปี:</span> ปี {{ request('year') + 543 }} ({{ request('year') }})
                @else
                    <span class="font-bold">ช่วงวันที่:</span> 
                    {{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d/m/Y') : 'ทั้งหมด' }} 
                    ถึง 
                    {{ $end_date ? \Carbon\Carbon::parse($end_date)->format('d/m/Y') : 'ปัจจุบัน' }}
                @endif
            </td>
            <td width="50%" class="text-right">
                <span class="font-bold">วันที่พิมพ์:</span> {{ date('d/m/Y H:i') }} น.
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="10%">เลขที่คำขอ</th>
                <th width="15%">ผู้ขอเบิก</th>
                <th width="15%">สายงาน/แผนก</th>
                <th class="text-center" width="12%">วันที่ขอเบิก</th>
                <th class="text-center" width="8%">รายการ</th>
                <th class="text-right" width="12%">ราคารวม (บาท)</th>
                <th class="text-center" width="12%">สถานะการจัดส่ง</th>
                <th class="text-center" width="16%">สถานะคำขอ</th>
            </tr>
        </thead>
        <tbody>
        @forelse($requisitions as $req)
            <tr>
                <td class="text-center font-bold">{{ $req->requisitions_code ?? '-' }}</td>
                <td>{{ optional($req->user)->fullname ? ('คุณ' . $req->user->fullname) : '-' }}</td>
                <td>
                    <div style="font-size: 13px;">
                        {{ optional(optional($req->user)->section)->section_code ?? '-' }} /
                        {{ optional(optional($req->user)->department)->department_name ?? '-' }}
                    </div>
                </td>
                <td class="text-center">{{ optional($req->request_date)->format('d/m/Y') ?? '-' }}</td>
                <td class="text-center">{{ $req->requisition_items->count() ?? 0 }}</td>
                <td class="text-right">{{ number_format((float) ($req->total_price ?? 0), 2) }}</td>
                <td class="text-center">{{ $req->packing_status_label ?? '—' }}</td>
                <td class="text-center">{{ $req->status_label ?? '—' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 40px 0; color: #94a3b8;">ไม่พบข้อมูลรายการเบิกในช่วงที่กำหนด</td>
            </tr>
        @endforelse
        </tbody>
        @if($requisitions->count() > 0)
        <tfoot>
            <tr style="background: #f8fafc;">
                <td colspan="5" class="text-right font-bold">รวมทั้งสิ้น</td>
                <td class="text-right font-bold">{{ number_format($requisitions->sum('total_price'), 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        * เอกสารนี้สร้างขึ้นโดยระบบอัตโนมัติจาก HAMSSYSTEM Dashboard
    </div>
</body>
</html>
