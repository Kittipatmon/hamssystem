<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แบบฟอร์มขออนุญาตนำญาติเข้าพักอาศัย_{{ $guest->resident_guest_code }}</title>
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
            height: 140px;
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
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
            vertical-align: middle;
            text-align: center;
            line-height: 12px;
            font-size: 14px;
            font-family: 'DejaVu Sans', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <h1 class="logo-text">Kumwell</h1>
        </div>

        <div class="title">
            แบบฟอร์มขออนุญาตนำญาติเข้าพักอาศัยบ้านพัก
        </div>

        <div class="form-row" style="text-align: right; margin-bottom: 20px;">
            วันที่ <span class="dotted-line" style="width: 30px;">{{ \Carbon\Carbon::parse($guest->request_date)->format('d') }}</span> 
            เดือน <span class="dotted-line" style="width: 80px;">{{ \Carbon\Carbon::parse($guest->request_date)->translatedFormat('F') }}</span> 
            พ.ศ. <span class="dotted-line" style="width: 40px;">{{ \Carbon\Carbon::parse($guest->request_date)->year + 543 }}</span>
        </div>

        <div class="content">
            <div class="form-row row">
                ข้าพเจ้า นาย/นาง/นางสาว <span class="dotted-line" style="width: 120px;">{{ $guest->first_name }}</span> นามสกุล <span class="dotted-line" style="width: 120px;">{{ $guest->last_name }}</span>
            </div>
            <div class="form-row row">
                ตำแหน่ง <span class="dotted-line" style="width: 130px;">{{ $guest->position }}</span> แผนก <span class="dotted-line" style="width: 100px;">{{ $guest->department }}</span> ฝ่าย <span class="dotted-line" style="width: 100px;">{{ $guest->section }}</span>
            </div>
            <div class="row">
                ซึ่งอยู่บ้านพักพนักงาน @if($guest->residence_type == 'โรงงานสำนักงานใหญ่') <span class="check-box">&#10003;</span> @else <span class="check-box"></span> @endif โรงงานบางใหญ่ @if($guest->residence_type == 'โรงงานไทรใหญ่') <span class="check-box">&#10003;</span> @else <span class="check-box"></span> @endif โรงงานไทรใหญ่
            </div>
            <div class="form-row row">
                ห้อง <span class="dotted-line" style="width: 100px;">{{ $guest->room_number }}</span> มีความประสงค์ขออนุญาตนำผู้มีรายชื่อดังต่อไปนี้
            </div>

            @php $members = $guest->members; @endphp
            @for ($i = 0; $i < 3; $i++)
            <div class="form-row row">
                {{ $i+1 }}. <span class="dotted-line" style="width: 280px;">{{ $members[$i]->full_name ?? '' }}</span> อายุ <span class="dotted-line" style="width: 40px;">{{ $members[$i]->age ?? '' }}</span> โทรศัพท์ <span class="dotted-line" style="width: 120px;"></span>
            </div>
            @endfor

            <div class="form-row row">
                บุคคลข้างต้นเกี่ยวข้องเป็น <span class="dotted-line" style="width: 250px;">{{ $guest->relationship }}</span> เข้ามาในบ้านพัก
            </div>
            <div class="form-row row">
                ตั้งแต่วันที่ <span class="dotted-line" style="width: 30px;">{{ \Carbon\Carbon::parse($guest->start_date)->format('d') }}</span> เดือน <span class="dotted-line" style="width: 80px;">{{ \Carbon\Carbon::parse($guest->start_date)->translatedFormat('F') }}</span> พ.ศ. <span class="dotted-line" style="width: 40px;">{{ \Carbon\Carbon::parse($guest->start_date)->year + 543 }}</span> เวลา <span class="dotted-line" style="width: 60px;">{{ $guest->start_time }}</span> น.
            </div>
            <div class="form-row row">
                จนถึงวันที่ <span class="dotted-line" style="width: 30px;">{{ \Carbon\Carbon::parse($guest->end_date)->format('d') }}</span> เดือน <span class="dotted-line" style="width: 80px;">{{ \Carbon\Carbon::parse($guest->end_date)->translatedFormat('F') }}</span> พ.ศ. <span class="dotted-line" style="width: 40px;">{{ \Carbon\Carbon::parse($guest->end_date)->year + 543 }}</span> เวลา <span class="dotted-line" style="width: 60px;">{{ $guest->end_time }}</span> น.
            </div>
            <div class="row">
                รวมเป็นระยะเวลา <span class="dotted-line" style="width: 60px;">{{ $guest->total_days }}</span> วัน
            </div>
            
            <div class="row" style="text-align: center; margin-top: 20px; font-weight: bold;">
                จึงเรียนมาเพื่อโปรดพิจารณา
            </div>
        </div>

        <table class="box-grid">
            <tr>
                <td>
                    <div class="box-title">[1] ผู้ขอบ้านพัก</div>
                    <div style="margin-top: 10px;">
                        ลงชื่อ <span class="dotted-line" style="width: 180px;"></span> 
                    </div>
                    <div style="margin-top: 10px;">
                        วันที่ <span class="dotted-line" style="width: 25px;"></span> / <span class="dotted-line" style="width: 25px;"></span> / <span class="dotted-line" style="width: 35px;"></span>
                    </div>
                    <div style="margin-top: 15px; font-size: 13px;">
                        เอกสารที่ใช้ประกอบ<br>
                        - สำเนาบัตรประชาชน (ผู้ขอเข้าพักอาศัย)
                    </div>
                </td>
                <td>
                    <div class="box-title">[2] ความเห็นของผู้บังคับบัญชา</div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $guest->commander_status == 1 ? '&#10003;' : '' !!}</span> อนุมัติ
                    </div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $guest->commander_status == 2 ? '&#10003;' : '' !!}</span> ไม่อนุมัติ เนื่องจาก <span class="dotted-line" style="width: 130px;">{{ $guest->commander_comment }}</span>
                    </div>
                    <div style="margin-top: 30px;">
                        ลงชื่อ <span class="dotted-line" style="width: 180px;">{{ $guest->commander_id ? \App\Models\User::find($guest->commander_id)->fullname : '' }}</span> 
                    </div>
                    <div style="margin-top: 10px;">
                        วันที่ <span class="dotted-line" style="width: 25px;">{{ $guest->commander_date ? \Carbon\Carbon::parse($guest->commander_date)->format('d') : '' }}</span> / <span class="dotted-line" style="width: 25px;">{{ $guest->commander_date ? \Carbon\Carbon::parse($guest->commander_date)->format('m') : '' }}</span> / <span class="dotted-line" style="width: 35px;">{{ $guest->commander_date ? \Carbon\Carbon::parse($guest->commander_date)->year + 543 : '' }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="box-title">[3] ผู้จัดการแผนกจัดการและบำรุงอาคาร</div>
                    <div style="margin-top: 30px;">
                        ลงชื่อ <span class="dotted-line" style="width: 180px;">{{ $guest->managerhams_id ? \App\Models\User::find($guest->managerhams_id)->fullname : '' }}</span> 
                    </div>
                    <div style="margin-top: 10px;">
                        วันที่ <span class="dotted-line" style="width: 25px;">{{ $guest->managerhams_date ? \Carbon\Carbon::parse($guest->managerhams_date)->format('d') : '' }}</span> / <span class="dotted-line" style="width: 25px;">{{ $guest->managerhams_date ? \Carbon\Carbon::parse($guest->managerhams_date)->format('m') : '' }}</span> / <span class="dotted-line" style="width: 35px;">{{ $guest->managerhams_date ? \Carbon\Carbon::parse($guest->managerhams_date)->year + 543 : '' }}</span>
                    </div>
                </td>
                <td>
                    <div class="box-title">[4] กรรมการบ้านพัก</div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $guest->Committee_status == 1 ? '&#10003;' : '' !!}</span> อนุมัติ
                    </div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $guest->Committee_status == 2 ? '&#10003;' : '' !!}</span> ไม่อนุมัติ เนื่องจาก <span class="dotted-line" style="width: 130px;">{{ $guest->Committee_comment }}</span>
                    </div>
                    <div style="margin-top: 30px;">
                        ลงชื่อ <span class="dotted-line" style="width: 180px;">{{ $guest->Committee_id ? \App\Models\User::find($guest->Committee_id)->fullname : '' }}</span> 
                    </div>
                    <div style="margin-top: 10px;">
                         วันที่ <span class="dotted-line" style="width: 25px;">{{ $guest->Committee_date ? \Carbon\Carbon::parse($guest->Committee_date)->format('d') : '' }}</span> / <span class="dotted-line" style="width: 25px;">{{ $guest->Committee_date ? \Carbon\Carbon::parse($guest->Committee_date)->format('m') : '' }}</span> / <span class="dotted-line" style="width: 35px;">{{ $guest->Committee_date ? \Carbon\Carbon::parse($guest->Committee_date)->year + 543 : '' }}</span>
                    </div>
                </td>
            </tr>
        </table>

        <div style="margin-top: 20px; font-size: 14px;">
            <div style="font-weight: bold; text-decoration: underline;">เงื่อนไขการขอเข้าพักอาศัย</div>
            - กรณีพักอาศัยไม่เกิน 15 วัน ให้เป็นอำนาจอนุมัติของกรรมการบ้านพัก<br>
            - กรณีพักอาศัยเกินกว่า 15 วัน ให้ดำเนินการเขียน MEMO ถึงผู้บังคับบัญชา
        </div>

        <div class="footer-ref">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left;">QF-HAMS-05 Rev.00 วันที่เริ่มใช้งาน 20/06/2025</td>
                    <td style="text-align: right;">หน้า 1 / 1</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
