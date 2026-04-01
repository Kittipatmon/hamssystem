<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แบบฟอร์มข้อตกลง_{{ $agreement->agreement_code }}</title>
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
            height: 120px;
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
        .page-break {
            page-break-before: always;
        }
        ol {
            margin-left: 30px;
        }
        ol li {
            margin-bottom: 8px;
            text-align: justify;
        }
    </style>
</head>
<body>
    <!-- PAGE 1 -->
    <div class="container">
        <div class="logo-section">
            <h1 class="logo-text">Kumwell</h1>
        </div>

        <div class="title">
            แบบฟอร์มข้อตกลงการเข้าพักอาศัยบ้านพักพนักงาน
        </div>

        <div class="form-row" style="text-align: right; margin-bottom: 20px;">
            วันที่ <span class="dotted-line" style="width: 30px;">{{ \Carbon\Carbon::parse($agreement->agreement_date)->format('d') }}</span> 
            เดือน <span class="dotted-line" style="width: 80px;">{{ \Carbon\Carbon::parse($agreement->agreement_date)->translatedFormat('F') }}</span> 
            พ.ศ. <span class="dotted-line" style="width: 40px;">{{ \Carbon\Carbon::parse($agreement->agreement_date)->year + 543 }}</span>
        </div>

        <div class="content">
            @php
                $names = explode(' ', $agreement->full_name, 2);
                $firstName = $names[0] ?? '';
                $lastName = $names[1] ?? '';
            @endphp
            <div class="form-row row">
                ข้าพเจ้า นาย/นาง/นางสาว <span class="dotted-line" style="width: 120px;">{{ $firstName }}</span> นามสกุล <span class="dotted-line" style="width: 120px;">{{ $lastName }}</span>
            </div>
            <div class="form-row row">
                ตำแหน่ง <span class="dotted-line" style="width: 130px;">{{ $agreement->position }}</span> แผนก <span class="dotted-line" style="width: 100px;">{{ $agreement->department }}</span> ฝ่าย <span class="dotted-line" style="width: 100px;">{{ $agreement->section }}</span> ซึ่งต่อไปนี้เรียกว่า "ผู้พักอาศัย"
            </div>
            <div class="row">
                ขอทำข้อตกลงกับ บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน) เพื่อให้การเข้าพักอาศัยเป็นไปตามระเบียบของ บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน) ว่าด้วย การให้พนักงานเข้าพักอาศัยในบ้านพักของบริษัทฯ โดยมีข้อตกลงดังต่อไปนี้
            </div>
            <div class="row">
                1. ผู้พักอาศัยรับทราบระเบียบต่างๆ ที่กำหนดไว้อใน คู่มือระเบียบบ้านพักพนักงาน และยินยอมปฏิบัติตามอย่างเคร่งครัด
            </div>
            <div class="row">
                2. บริษัทฯ มีสิทธิ์ยกเลิกข้อตกลงการเข้าพักอาศัยฉบับนี้ โดยไม่ต้องแจ้งให้ทราบล่วงหน้า และผู้พักอาศัยต้องย้ายออกจากบ้านพักตามข้อกำหนดในระเบียบฯ
            </div>
            <div class="form-row row">
                3. ผู้พักอาศัยได้รับกุญแจ ห้อง <span class="dotted-line" style="width: 100px;">{{ $agreement->residence_address }}</span> ชั้น <span class="dotted-line" style="width: 60px;">{{ $agreement->residence_floor }}</span> จำนวน <span class="dotted-line" style="width: 40px;">2</span> ดอก
            </div>
            <div class="row">
                4. เมื่อผู้พักอาศัยย้ายออกจากบ้านพัก ต้องส่งมอบกุญแจบ้านพักให้กรรมการบ้านพักในเวลาที่กำหนด หากปรากฏว่ามีสิ่งใดเสียหาย ผู้พักอาศัยต้องรับผิดชอบหรือชดใช้ค่าเสียหายจนครบถ้วนสมบูรณ์
                <br>
                <span style="margin-left: 20px;">4.1 กรณีพนักงานย้ายออกจากบ้านพักโดยไม่แจ้งให้ทราบล่วงหน้า ทางบริษัทฯ มีสิทธิ์ระงับการจ่ายเงินเดือนจนกว่าพนักงานจะดำเนินการทำเรื่องขอย้ายออกตามระเบียบบ้านพักพนักงานเสร็จสิ้นสมบูรณ์</span>
            </div>
            <div class="row" style="text-indent: 40px;">
                ผู้พักอาศัยได้อ่านข้อความในข้อตกลงฉบับนี้จนเป็นที่เข้าใจโดยตลอด รวมทั้งได้ทราบเงื่อนไขและข้อปฏิบัติในการพักอาศัยตามระเบียบฯ การให้พนักงานเข้าพักอาศัยในบ้านพักของบริษัทฯ จึงได้ลงลายมือชื่อไว้เป็นหลักฐาน
            </div>
        </div>

        <table class="box-grid">
            <tr>
                <td>
                    <div class="box-title">[1] ผู้ขอบ้านพัก</div>
                    <div style="margin-top: 30px;">
                        ลงชื่อ <span class="dotted-line" style="width: 180px;"></span> 
                    </div>
                    <div style="margin-top: 10px;">
                        วันที่ <span class="dotted-line" style="width: 25px;"></span> / <span class="dotted-line" style="width: 25px;"></span> / <span class="dotted-line" style="width: 35px;"></span>
                    </div>
                </td>
                <td>
                    <div class="box-title">[2] ความเห็นของผู้บังคับบัญชา</div>
                    <div style="margin-top: 30px;">
                        ลงชื่อ <span class="dotted-line" style="width: 180px;">{{ $agreement->commander_id ? \App\Models\User::find($agreement->commander_id)->fullname : '' }}</span> 
                    </div>
                    <div style="margin-top: 10px;">
                        วันที่ <span class="dotted-line" style="width: 25px;">{{ $agreement->commander_date ? \Carbon\Carbon::parse($agreement->commander_date)->format('d') : '' }}</span> / <span class="dotted-line" style="width: 25px;">{{ $agreement->commander_date ? \Carbon\Carbon::parse($agreement->commander_date)->format('m') : '' }}</span> / <span class="dotted-line" style="width: 35px;">{{ $agreement->commander_date ? \Carbon\Carbon::parse($agreement->commander_date)->year + 543 : '' }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="box-title">[3] กรรมการบ้านพัก</div>
                    <div style="margin-top: 30px;">
                        ลงชื่อ <span class="dotted-line" style="width: 180px;">{{ $agreement->Committee_id ? \App\Models\User::find($agreement->Committee_id)->fullname : '' }}</span> 
                    </div>
                    <div style="margin-top: 10px;">
                         วันที่ <span class="dotted-line" style="width: 25px;">{{ $agreement->Committee_date ? \Carbon\Carbon::parse($agreement->Committee_date)->format('d') : '' }}</span> / <span class="dotted-line" style="width: 25px;">{{ $agreement->Committee_date ? \Carbon\Carbon::parse($agreement->Committee_date)->format('m') : '' }}</span> / <span class="dotted-line" style="width: 35px;">{{ $agreement->Committee_date ? \Carbon\Carbon::parse($agreement->Committee_date)->year + 543 : '' }}</span>
                    </div>
                </td>
                <td>
                    <div class="box-title">[4] ผู้จัดการแผนกจัดการและบำรุงอาคาร</div>
                    <div style="margin-top: 30px;">
                        ลงชื่อ <span class="dotted-line" style="width: 180px;">{{ $agreement->managerhams_id ? \App\Models\User::find($agreement->managerhams_id)->fullname : '' }}</span> 
                    </div>
                    <div style="margin-top: 10px;">
                        วันที่ <span class="dotted-line" style="width: 25px;">{{ $agreement->managerhams_date ? \Carbon\Carbon::parse($agreement->managerhams_date)->format('d') : '' }}</span> / <span class="dotted-line" style="width: 25px;">{{ $agreement->managerhams_date ? \Carbon\Carbon::parse($agreement->managerhams_date)->format('m') : '' }}</span> / <span class="dotted-line" style="width: 35px;">{{ $agreement->managerhams_date ? \Carbon\Carbon::parse($agreement->managerhams_date)->year + 543 : '' }}</span>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer-ref">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left;">QF-HAMS-03 Rev.00 วันที่เริ่มใช้งาน 20/06/2025</td>
                    <td style="text-align: right;">หน้า 1 / 2</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- PAGE 2 -->
    <div class="page-break"></div>
    <div class="container">
        <div class="title" style="margin-top: 20px;">ข้อตกลงสำหรับผู้พักอาศัยบ้านพักพนักงาน</div>
        <div class="row" style="font-weight: bold; margin-bottom: 20px;">ข้อตกลงสำหรับผู้พักอาศัยบ้านพัก ต้องปฏิบัติตาม ดังนี้</div>
        
        <ol>
            <li>ผู้พักอาศัยต้องดูแลรักษาความสะอาดบ้านพักและบริเวณบ้านพักให้คงสภาพดีเสมอ</li>
            <li>ผู้พักอาศัยต้องใช้อุปกรณ์เครื่องใช้ไฟฟ้าที่ได้มาตรฐาน เพื่อป้องกันเหตุไฟฟ้าลัดวงจรและอัคคีภัยโดยจัดให้มีการตรวจสอบเป็นประจำทุกเดือน</li>
            <li>ผู้พักอาศัยร่วมดูแลรักษาทรัพย์สินภายในบ้านพัก บริเวณบ้านพัก รวมทั้งดูแลทรัพย์สินที่บริษัทฯ จัดให้เป็นสวัสดิการของส่วนกลาง</li>
            <li>ผู้พักอาศัยต้องเข้าร่วมการทำความสะอาดตามตารางที่กำหนดไว้ รวมทั้งการซ่อมบำรุงรักษาทรัพย์สินของบริษัทฯ ตามที่กรรมการบ้านพักนัดหมาย</li>
            <li>ห้ามเปลี่ยนแปลง ต่อเติมหรือกระทำการใดๆ ในบ้านพัก ก่อนการได้รับอนุญาต</li>
            <li>หากพบเห็นอุปกรณ์ ทรัพย์สินบ้านพักเกิดความชำรุดเสียหาย ให้ดำเนินการแจ้งกรรมการบ้านพักทันที</li>
            <li>ผู้พักอาศัย ต้องประกอบอาหารในบริเวณพื้นที่ส่วนกลางที่บริษัทฯ จัดไว้เท่านั้น</li>
            <li>ห้ามนำสัตว์เลี้ยงทุกชนิดเข้ามาภายในบริเวณบ้านพัก</li>
            <li>ห้ามนำเข้าและเสพสิ่งเสพติดทุกชนิด</li>
            <li>ห้ามก่อการทะเลาะวิวาท ไม่กระทำการใดๆ ให้มีเสียงดัง จนเป็นที่เดือดร้อนรำคาญแก่ผู้อื่น</li>
            <li>ห้ามนำอาวุธทุกชนิด อาทิเช่น ปืน กระสุนปืน วัตถุระเบิด และเชื้อเพลิงไวไฟเข้ามาในบริเวณบ้านพักโดยเด็ดขาด</li>
            <li>ห้ามสูบบุหรี่ บุหรี่ไฟฟ้าและอื่นๆ ภายในบริเวณบ้านพักโดยเด็ดขาด ยกเว้นบริเวณพื้นที่ที่จัดไว้ให้</li>
            <li>ห้ามเล่นการพนันทุกชนิด</li>
            <li>ห้ามบุคคลภายนอกที่ไม่ได้รับอนุญาตเข้ามาในพื้นที่บ้านพักโดยเด็ดขาด</li>
        </ol>

        <div class="footer-ref" style="margin-top: 100px;">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left;">QF-HAMS-03 Rev.00 วันที่เริ่มใช้งาน 20/06/2025</td>
                    <td style="text-align: right;">หน้า 2 / 2</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
