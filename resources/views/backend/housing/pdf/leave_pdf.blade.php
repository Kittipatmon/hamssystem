<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แบบฟอร์มขอย้ายออกจากบ้านพัก_{{ $leave->residence_leaves_code }}</title>
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
            font-family: 'THSarabunNew', sans-serif;
            font-size: 16px;
            line-height: 1.1;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 10px 40px;
        }
        .logo-section {
            text-align: right;
            margin-bottom: 0;
        }
        .logo-text {
            font-family: Arial, sans-serif;
            font-size: 28px;
            font-weight: bold;
            color: #D71920;
            margin: 0;
        }
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 10px;
        }
        .dotted-line {
            display: inline-block;
            border-bottom: 1px dotted #000;
            min-height: 14px;
            line-height: 1;
            padding: 0 5px;
            text-align: center;
            vertical-align: middle;
            margin-bottom: 3px;
        }
        .row {
            margin-bottom: 12px;
        }
        .form-row {
            white-space: nowrap;
        }
        .box-grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .box-grid td {
            width: 50%;
            border: 1px solid #000;
            padding: 10px;
            font-size: 15px;
            vertical-align: top;
            height: 160px;
        }
        .box-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .footer-ref {
            margin-top: 30px;
            font-size: 12px;
            width: 100%;
        }
        .check-box {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            margin-right: 5px;
            vertical-align: middle;
            text-align: center;
            line-height: 12px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <h1 class="logo-text">Kumwell</h1>
        </div>

        <div class="title">
            แบบฟอร์มขอย้ายออกจากบ้านพักพนักงาน
        </div>

        <div class="form-row" style="text-align: right; margin-bottom: 20px;">
            วันที่ <span class="dotted-line" style="width: 30px;">{{ \Carbon\Carbon::parse($leave->request_date)->format('d') }}</span> 
            เดือน <span class="dotted-line" style="width: 80px;">{{ \Carbon\Carbon::parse($leave->request_date)->translatedFormat('F') }}</span> 
            พ.ศ. <span class="dotted-line" style="width: 40px;">{{ \Carbon\Carbon::parse($leave->request_date)->year + 543 }}</span>
        </div>

        <div class="content">
            <div class="form-row row">
                ข้าพเจ้า นาย/นาง/นางสาว <span class="dotted-line" style="width: 120px;">{{ $leave->first_name }}</span> นามสกุล <span class="dotted-line" style="width: 120px;">{{ $leave->last_name }}</span>
            </div>
            <div class="form-row row">
                ตำแหน่ง <span class="dotted-line" style="width: 130px;">{{ $leave->position }}</span> แผนก <span class="dotted-line" style="width: 100px;">{{ $leave->department }}</span> ฝ่าย <span class="dotted-line" style="width: 100px;">{{ $leave->section }}</span>
            </div>
            <div class="row">
            <div class="row">
                อาศัยอยู่บ้านพักพนักงาน <span class="check-box">{!! $leave->residence_type == 'โรงงานสำนักงานใหญ่' ? '&#10003;' : '' !!}</span> โรงงานสำนักงานใหญ่ <span class="check-box">{!! $leave->residence_type == 'โรงงานไทรใหญ่' ? '&#10003;' : '' !!}</span> โรงงานไทรใหญ่
            </div>
            </div>
            <div class="form-row row">
                ห้อง <span class="dotted-line" style="width: 80px;">{{ $leave->room_number }}</span> ชั้น <span class="dotted-line" style="width: 50px;">{{ $leave->floor }}</span> ขอแจ้งความประสงค์ขอย้ายออกจากบ้านพักพนักงาน
            </div>
            <div class="form-row row">
                ตั้งแต่วันที่ <span class="dotted-line" style="width: 30px;">{{ \Carbon\Carbon::parse($leave->move_out_date)->format('d') }}</span> เดือน <span class="dotted-line" style="width: 80px;">{{ \Carbon\Carbon::parse($leave->move_out_date)->translatedFormat('F') }}</span> พ.ศ. <span class="dotted-line" style="width: 40px;">{{ \Carbon\Carbon::parse($leave->move_out_date)->year + 543 }}</span> เป็นต้นไป
            </div>
            <div class="row">
                เนื่องจาก <span class="dotted-line" style="width: 450px;">{{ $leave->reason }}</span>
            </div>
            
            <div class="row" style="margin-top: 20px;">
                เมื่อผู้พักอาศัยแจ้งย้ายออกจากบ้านพัก ต้องส่งมอบกุญแจบ้านพักให้กรรมการบ้านพักและย้ายออกภายในกำหนดระยะเวลา 7 วัน หลังจากแจ้งความประสงค์ หากปรากฏว่ามีสิ่งใดเสียหาย ผู้พักอาศัยต้องรับผิดชอบหรือชดใช้ค่าเสียหายจนครบถ้วนสมบูรณ์
            </div>
        </div>

        <table class="box-grid">
            <tr>
                <td rowspan="2">
                    <div class="box-title">[1] ผู้พักอาศัย</div>
                    <div style="margin-top: 50px;">
                        ลงชื่อ <span class="dotted-line" style="width: 180px;"></span> 
                    </div>
                    <div style="margin-top: 10px;">
                        วันที่ <span class="dotted-line" style="width: 25px;"></span> / <span class="dotted-line" style="width: 25px;"></span> / <span class="dotted-line" style="width: 35px;"></span>
                    </div>
                </td>
                <td>
                    <div class="box-title">[2] กรรมการบ้านพัก</div>
                    <div style="margin-bottom: 5px;">ตรวจสอบแล้ว</div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $leave->Committee_status == 1 ? '&#10003;' : '' !!}</span> ผ่าน พร้อมส่งมอบกุญแจ
                    </div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $leave->Committee_status == 2 ? '&#10003;' : '' !!}</span> ไม่ผ่าน เนื่องจาก <span class="dotted-line" style="width: 130px;">{{ $leave->Committee_comment }}</span>
                    </div>
                    <div style="margin-top: 20px;">
                        ลงชื่อ <span class="dotted-line" style="width: 180px;">{{ $leave->Committee_id ? \App\Models\User::find($leave->Committee_id)->fullname : '' }}</span> 
                    </div>
                    <div style="margin-top: 10px;">
                         วันที่ <span class="dotted-line" style="width: 25px;">{{ $leave->Committee_date ? \Carbon\Carbon::parse($leave->Committee_date)->format('d') : '' }}</span> / <span class="dotted-line" style="width: 25px;">{{ $leave->Committee_date ? \Carbon\Carbon::parse($leave->Committee_date)->format('m') : '' }}</span> / <span class="dotted-line" style="width: 35px;">{{ $leave->Committee_date ? \Carbon\Carbon::parse($leave->Committee_date)->year + 543 : '' }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="box-title">[3] ผู้จัดการแผนกจัดการและบำรุงอาคาร</div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $leave->managerhams_status == 1 ? '&#10003;' : '' !!}</span> ผ่าน พร้อมส่งมอบกุญแจ
                    </div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $leave->managerhams_status == 2 ? '&#10003;' : '' !!}</span> ไม่ผ่าน เนื่องจาก <span class="dotted-line" style="width: 130px;">{{ $leave->managerhams_comment }}</span>
                    </div>
                    <div style="margin-top: 30px;">
                        ลงชื่อ <span class="dotted-line" style="width: 180px;">{{ $leave->managerhams_id ? \App\Models\User::find($leave->managerhams_id)->fullname : '' }}</span> 
                    </div>
                    <div style="margin-top: 10px;">
                        วันที่ <span class="dotted-line" style="width: 25px;">{{ $leave->managerhams_date ? \Carbon\Carbon::parse($leave->managerhams_date)->format('d') : '' }}</span> / <span class="dotted-line" style="width: 25px;">{{ $leave->managerhams_date ? \Carbon\Carbon::parse($leave->managerhams_date)->format('m') : '' }}</span> / <span class="dotted-line" style="width: 35px;">{{ $leave->managerhams_date ? \Carbon\Carbon::parse($leave->managerhams_date)->year + 543 : '' }}</span>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer-ref">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left;">QF-HAMS-04 Rev.00 วันที่เริ่มใช้งาน 20/06/2025</td>
                    <td style="text-align: right;">หน้า 1 / 1</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
