<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบเบิกพัสดุ_{{ $requisition->requisitions_code }}</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        body {
            font-family: 'THSarabunNew', 'DejaVu Sans', sans-serif;
            font-size: 16px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
        }
        .header {
            width: 100%;
            margin-bottom: 20px;
        }
        .logo-section {
            width: 50%;
            float: left;
        }
        .code-section {
            width: 50%;
            float: right;
            text-align: right;
            font-size: 14px;
        }
        .logo-text {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 36px;
            font-weight: bold;
            color: #D71920;
            margin: 0;
            letter-spacing: -1px;
        }
        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            clear: both;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        .info-section {
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #B4C6E7; /* Light Blue from reference */
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            background-color: #FFF2CC; /* Light Yellow from reference */
            font-weight: bold;
        }
        .remarks {
            margin-top: 20px;
            font-size: 14px;
        }
        .signature-section {
            margin-top: 40px;
            width: 100%;
        }
        .signature-box {
            width: 45%;
            float: left;
            text-align: center;
        }
        .signature-box.right {
            float: right;
        }
        .signature-line {
            border-bottom: 1px dotted #000;
            width: 180px;
            margin: 10px auto;
        }
        .footer-note {
            clear: both;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-section">
                <h1 class="logo-text">Kumwell</h1>
            </div>
            <div class="code-section">
                <p>เลขที่ใบเบิก: {{ $requisition->requisitions_code }}</p>
            </div>
        </div>

        <div class="title">
            รายละเอียดการเบิกอุปกรณ์
        </div>

        <div class="info-section">
            <p><strong>วันที่:</strong> {{ optional($requisition->request_date)->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') ?? now()->locale('th')->addYears(543)->isoFormat('D MMMM YYYY') }}</p>
            <p><strong>เบิกของโดย:</strong> คุณ{{ $requisition->user->fullname ?? '-' }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 10%;">ลำดับ</th>
                    <th style="width: 50%;">รายการอุปกรณ์</th>
                    <th style="width: 10%;">จำนวน</th>
                    <th style="width: 15%;">ราคา</th>
                    <th style="width: 15%;">ราคารวม</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requisition->requisition_items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">{{ $item->item->name ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->item->per_unit ?? 0, 2) }}</td>
                    <td class="text-right">{{ number_format(($item->item->per_unit ?? 0) * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4" class="text-right">ราคารวมทั้งหมด</td>
                    <td class="text-right">{{ number_format($requisition->total_price, 2) }} บาท</td>
                </tr>
            </tfoot>
        </table>

        <div class="remarks">
            <p><strong>หมายเหตุ :</strong> {{ $requisition->remarks ?? '-' }}</p>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p>อนุมัติโดย: {{ optional($requisition->approve_user)->fullname ?? '......................................................' }}</p>
                <p>วันที่: {{ $requisition->approve_date ? optional($requisition->approve_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') : '..................... / ..................... / .....................' }}</p>
                <p>หมายเหตุ: {{ $requisition->approve_comment ?? '........................................................' }}</p>
            </div>
            <div class="signature-box right">
                <p>จัดเตรียมของโดย: {{ optional($requisition->packing_staff)->fullname ?? '......................................................' }}</p>
                <p>วันที่: {{ $requisition->packing_staff_date ? optional($requisition->packing_staff_date)->locale('th')->addYears(543)->isoFormat('D MMM YYYY') : '..................... / ..................... / .....................' }}</p>
                <p>หมายเหตุ: ........................................................</p>
            </div>
        </div>
    </div>
</body>
</html>
