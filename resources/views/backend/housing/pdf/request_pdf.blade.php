<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แบบฟอร์มขอเข้าพัก_{{ $requestData->requests_code }}</title>
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
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 10px 30px;
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
            margin-bottom: 15px;
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
        .section-title {
            font-weight: bold;
            margin-top: 10px;
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
        .row {
            margin-bottom: 8px;
            clear: both;
        }
        .form-row {
            white-space: nowrap;
        }
        .col {
            display: inline-block;
            vertical-align: top;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: right;
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
            font-size: 14px;
            vertical-align: top;
            height: 140px;
        }
        .box-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .signature-line {
            margin-top: 15px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <h1 class="logo-text">Kumwell</h1>
        </div>

        <div class="title">
            แบบฟอร์มขอเข้าอยู่อาศัยบ้านพักพนักงาน
        </div>

        <div class="form-row" style="text-align: right; margin-bottom: 15px;">
            วันที่ <span class="dotted-line" style="width: 40px;">{{ \Carbon\Carbon::parse($requestData->request_date)->format('d') }}</span> 
            เดือน <span class="dotted-line" style="width: 80px;">{{ \Carbon\Carbon::parse($requestData->request_date)->translatedFormat('F') }}</span> 
            พ.ศ. <span class="dotted-line" style="width: 50px;">{{ \Carbon\Carbon::parse($requestData->request_date)->year + 543 }}</span>
        </div>

        <div class="content">
            <div class="row">
                ด้วยข้าพเจ้ามีความต้องการเข้าพักอาศัยในบ้านพักของพนักงาน ที่ 
                <span class="check-box">{!! $requestData->site == 'โรงงานบางใหญ่' ? '&#10003;' : '' !!}</span> โรงงานบางใหญ่ 
                <span class="check-box">{!! $requestData->site == 'โรงงานไทรใหญ่' ? '&#10003;' : '' !!}</span> โรงงานไทรใหญ่
            </div>
            <div class="row">โดยมีรายละเอียดข้อมูลส่วนตัวของข้าพเจ้าเพื่อประกอบการพิจารณาดังนี้</div>
            
            <div class="form-row row">
                1. {{ $requestData->title ?? 'นาย/นาง/นางสาว' }} <span class="dotted-line" style="width: 120px;">{{ $requestData->first_name }}</span> นามสกุล <span class="dotted-line" style="width: 120px;">{{ $requestData->last_name }}</span>
            </div>

            <div class="form-row row">
                2. ตำแหน่ง <span class="dotted-line" style="width: 130px;">{{ $requestData->position }}</span> แผนก <span class="dotted-line" style="width: 100px;">{{ $requestData->department }}</span> ฝ่าย <span class="dotted-line" style="width: 100px;">{{ $requestData->section }}</span> อายุงาน <span class="dotted-line" style="width: 40px;">{{ $requestData->age_work }}</span> ปี
            </div>

            <div class="form-row row">
                3. โทรศัพท์ <span class="dotted-line" style="width: 150px;">{{ $requestData->phone }}</span> สถานภาพ 
                <span class="check-box">{!! $requestData->marital_status == 'โสด' ? '&#10003;' : '' !!}</span> โสด 
                <span class="check-box">{!! $requestData->marital_status == 'สมรส' ? '&#10003;' : '' !!}</span> สมรส
            </div>

            <div class="form-row row">
                4. ภูมิลำเนาเดิม บ้านเลขที่ <span class="dotted-line" style="width: 130px;">{{ $requestData->address_original }}</span> ต. <span class="dotted-line" style="width: 80px;">{{ $requestData->address_original_subdistrict }}</span> อ. <span class="dotted-line" style="width: 80px;">{{ $requestData->address_original_district }}</span> จ. <span class="dotted-line" style="width: 90px;">{{ $requestData->address_original_province }}</span>
            </div>

            <div class="form-row row">
                5. ปัจจุบัน บ้านเลขที่ <span class="dotted-line" style="width: 80px;">{{ $requestData->address_current }}</span> ต. <span class="dotted-line" style="width: 70px;">{{ $requestData->address_current_subdistrict }}</span> อ. <span class="dotted-line" style="width: 70px;">{{ $requestData->address_current_district }}</span> จ. <span class="dotted-line" style="width: 80px;">{{ $requestData->address_current_province }}</span>
            </div>
            <div class="row" style="padding-left: 20px;">
                เป็น <span class="check-box">{!! $requestData->current_house_type == 'บ้านเช่า' ? '&#10003;' : '' !!}</span> บ้านเช่า 
                <span class="check-box">{!! $requestData->current_house_type == 'บ้านตนเอง' ? '&#10003;' : '' !!}</span> บ้านตนเอง 
                <span class="check-box">{!! !in_array($requestData->current_house_type, ['บ้านเช่า', 'บ้านตนเอง']) && !empty($requestData->current_house_type) ? '&#10003;' : '' !!}</span> อื่นๆ ระบุ <span class="dotted-line" style="width: 200px;">{{ !in_array($requestData->current_house_type, ['บ้านเช่า', 'บ้านตนเอง']) ? $requestData->current_house_type : '' }}</span>
            </div>

            <div class="form-row row">
                6. ชื่อคู่สมรส <span class="dotted-line" style="width: 250px;">{{ $requestData->spouse_name }}</span> อาชีพ <span class="dotted-line" style="width: 200px;">{{ $requestData->spouse_occupation }}</span>
            </div>

            <div class="form-row row">
                7. สถานที่ทำงานคู่สมรส <span class="dotted-line" style="width: 300px;">{{ $requestData->workplace_spouse }}</span> โทรศัพท์ <span class="dotted-line" style="width: 150px;">{{ $requestData->spouse_phone }}</span>
            </div>

            <div class="row">
                8. จำนวนคนที่จะเข้าพักอาศัย <span class="dotted-line" style="width: 40px;">{{ $requestData->number_of_residents }}</span> คน
            </div>

            @php $dependents = $requestData->dependents; @endphp
            @for ($i = 0; $i < 3; $i++)
            <div class="form-row row">
                8.{{ $i+1 }} <span class="dotted-line" style="width: 200px;">{{ $dependents[$i]->full_name ?? '' }}</span> อายุ <span class="dotted-line" style="width: 25px;">{{ $dependents[$i]->age ?? '' }}</span> ปี เกี่ยวข้องเป็น <span class="dotted-line" style="width: 130px;">{{ $dependents[$i]->relation ?? '' }}</span>
            </div>
            @endfor

            <div class="row">
                9. เหตุผลที่ขอบ้านพัก <span class="dotted-line" style="width: 500px;">{{ $requestData->residence_reason }}</span>
            </div>

            <div class="row" style="margin-top: 15px; text-align: center; font-weight: bold;">
                ข้าพเจ้าขอรับรองว่าข้อมูลข้างต้นนี้เป็นความจริงทุกประการ หากข้าพเจ้าให้ข้อมูลเป็นเท็จ ข้าพเจ้ายินยอมให้บริษัทฯ
            </div>
            <div class="row" style="text-align: center; font-weight: bold;">
                ลงโทษทางวินัย และให้ย้ายออกจากบ้านพักโดยทันที
            </div>
        </div>

        <table class="box-grid">
            <tr>
                <td>
                    <div class="box-title">[1] ผู้ขอบ้านพัก</div>
                    <div style="margin-top: 30px;">
                        ลงชื่อ <span class="dotted-line" style="width: 200px;"></span> 
                    </div>
                    <div style="margin-top: 10px;">
                        วันที่ <span class="dotted-line" style="width: 30px;"></span> / <span class="dotted-line" style="width: 30px;"></span> / <span class="dotted-line" style="width: 40px;"></span>
                    </div>
                    <div style="margin-top: 30px; text-decoration: underline;">เอกสารที่ใช้ประกอบ</div>
                    <div style="font-size: 12px;">-สำเนาบัตรประชาชน (ผู้ร้องขอและผู้อาศัย)</div>
                </td>
                <td>
                    <div class="box-title">[2] ความเห็นของผู้บังคับบัญชา</div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $requestData->commander_status == 1 ? '&#10003;' : '' !!}</span> อนุมัติ
                    </div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $requestData->commander_status == 2 ? '&#10003;' : '' !!}</span> ไม่อนุมัติ เนื่องจาก <span class="dotted-line" style="width: 150px;">{{ $requestData->commander_status == 2 ? $requestData->commander_comment : '' }}</span>
                    </div>
                    <div style="margin-top: 5px; border-bottom: 1px dotted #000; height: 15px;"></div>
                    <div style="margin-top: 20px;">
                        ลงชื่อ <span class="dotted-line" style="width: 200px;">{{ $requestData->commander_id ? \App\Models\User::find($requestData->commander_id)->fullname : '' }}</span> 
                    </div>
                    <div style="margin-top: 10px;">
                        วันที่ <span class="dotted-line" style="width: 30px;">{{ $requestData->commander_date ? \Carbon\Carbon::parse($requestData->commander_date)->format('d') : '' }}</span> / <span class="dotted-line" style="width: 30px;">{{ $requestData->commander_date ? \Carbon\Carbon::parse($requestData->commander_date)->format('m') : '' }}</span> / <span class="dotted-line" style="width: 40px;">{{ $requestData->commander_date ? \Carbon\Carbon::parse($requestData->commander_date)->year + 543 : '' }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="box-title">[3] ผู้จัดการแผนกจัดการและบำรุงอาคาร</div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $requestData->managerhams_status == 1 ? '&#10003;' : '' !!}</span> อนุมัติ
                    </div>
                    <div style="margin-top: 5px;">
                        <span class="check-box">{!! $requestData->managerhams_status == 2 ? '&#10003;' : '' !!}</span> ไม่อนุมัติ เนื่องจาก <span class="dotted-line" style="width: 150px;">{{ $requestData->managerhams_status == 2 ? $requestData->managerhams_comment : '' }}</span>
                    </div>
                    <div style="margin-top: 5px; border-bottom: 1px dotted #000; height: 15px;"></div>
                    <div style="margin-top: 20px;">
                        ลงชื่อ <span class="dotted-line" style="width: 200px;">{{ $requestData->managerhams_id ? \App\Models\User::find($requestData->managerhams_id)->fullname : '' }}</span> 
                    </div>
                    <div style="margin-top: 10px;">
                         วันที่ <span class="dotted-line" style="width: 30px;">{{ $requestData->managerhams_date ? \Carbon\Carbon::parse($requestData->managerhams_date)->format('d') : '' }}</span> / <span class="dotted-line" style="width: 30px;">{{ $requestData->managerhams_date ? \Carbon\Carbon::parse($requestData->managerhams_date)->format('m') : '' }}</span> / <span class="dotted-line" style="width: 40px;">{{ $requestData->managerhams_date ? \Carbon\Carbon::parse($requestData->managerhams_date)->year + 543 : '' }}</span>
                    </div>
                </td>
                <td>
                    <div class="box-title">[4] กรรมการบ้านพัก</div>
                    <div style="margin-top: 30px;">
                        ลงชื่อ <span class="dotted-line" style="width: 200px;">{{ $requestData->Committee_id ? \App\Models\User::find($requestData->Committee_id)->fullname : '' }}</span> 
                    </div>
                    <div style="margin-top: 10px;">
                        วันที่ <span class="dotted-line" style="width: 30px;">{{ $requestData->Committee_date ? \Carbon\Carbon::parse($requestData->Committee_date)->format('d') : '' }}</span> / <span class="dotted-line" style="width: 30px;">{{ $requestData->Committee_date ? \Carbon\Carbon::parse($requestData->Committee_date)->format('m') : '' }}</span> / <span class="dotted-line" style="width: 40px;">{{ $requestData->Committee_date ? \Carbon\Carbon::parse($requestData->Committee_date)->year + 543 : '' }}</span>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            QF-HAMS-02 Rev.00 วันที่เริ่มใช้งาน 20/06/2025
        </div>
    </div>
</body>
</html>
