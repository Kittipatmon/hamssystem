<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานการเบิก PDF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
        body,
        div,
        thead,
        table,
        th,
        td,
        p {
            font-family: 'TH Sarabun New', sans-serif;
            font-size: 18px;
            font-weight: 400;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        thead,
        table,
        th,
        td {
            font-family: 'TH Sarabun New', sans-serif !important;

        }

        th {
            background-color: rgb(170, 188, 228);
            font-size: 20px;
            font-family: 'TH Sarabun New', sans-serif !important;
        }

        .tdder1 {
            border: 1px;
            text-align: center;
        }

        .tdder2 {
            text-align: center;
            border: 1px solid black;
        }

         .tdder3 {
            text-align: right;
            border: 1px solid black;
        }

         .tdder4 {
            text-align: center;
            border: 1px solid black;
        }

        .font1 {
            font-family: 'TH Sarabun New', sans-serif !important;
            font-size: 18px;
        }

        .fontbold {
            font-family: 'TH Sarabun New', sans-serif !important;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <table>
            <tr>
                <td><span class="mb-0">บริษัท คัมเวล คอร์ปอเรชั่น จำกัด (มหาชน)</span></td>
                <td align="right">เลขที่ใบเบิก {{ $requisition->requisitions_code }}</td>
            </tr>
        </table>

        <p align="center" style="font-size: 25px;">รายละเอียดการเบิกอุปกรณ์</p>
        <div class="row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <p style="margin: 0;">
                วันที่:
                {{
                    \Carbon\Carbon::parse($requisition->request_date)
                        ->locale('th')
                        ->translatedFormat('d F Y') 
                }}
            </p>
            <p style="margin: 0;">
                เบิกของโดย: {{ $requisition->user->name }}</p>
        </div>
        @php
        $has_unit = $requisition->requisition_items->where('quantity', '>', 0)->count() > 0;
        $has_pack = $requisition->requisition_items->where('quantity_pack', '>', 0)->count() > 0;
        $total_unit = 0;
        $total_pack = 0;
        @endphp

        <table class="table table-bordered mb-0" style="background: #f8fbff;">
            <thead style="background: #d9eaff;">
                <tr class="text-center">
                    <th style="width: 3%; background-color:rgb(247, 237, 182);">ลำดับ</th>
                    <th style="width: 20%;">รายการอุปกรณ์</th>
                    <th style="width: 8%;">จำนวน</th>
                    <th style="width: 10%;">ราคา</th>
                    <th style="width: 12%;">ราคารวม</th>
                    <!-- <th style="background-color:rgb(247, 237, 182); width: 1%;"></th>
                    <th style="width: 10%;">จำนวน</th>
                    <th style="width: 12%;">ราคา</th>
                    <th style="width: 15%;">ราคารวม(แพ็ค)</th> -->
                    <!-- <th style="width: 10%; background-color:rgb(247, 237, 182);">
                        <span class="text-center">ตรวจสอบ</span>
                    </th> -->
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ($requisition_items as $requisition_item)
                <tr>
                    <td style="background-color:rgb(247, 237, 182);" class="tdder2">{{ $i++ }}</td>
                    <td class="tdder2">{{ $requisition_item->item->name ?? '-' }}</td>
                    <td class="text-end tdder2">
                        @if($requisition_item->quantity > 0)
                        {{ $requisition_item->quantity }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="text-end tdder2">
                        @if($requisition_item->quantity > 0)
                        {{ number_format($requisition_item->item->per_unit, 2) }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="text-end tdder2">
                        @if($requisition_item->quantity > 0)
                        {{ number_format($requisition_item->item->per_unit * $requisition_item->quantity, 2) }}
                        @php $total_unit += $requisition_item->item->per_unit * $requisition_item->quantity; @endphp
                        @else
                        -
                        @endif
                    </td>
                    <!-- <td style="background-color:rgb(247, 237, 182);"></td>
                    <td class="text-end tdder2">
                        @if($requisition_item->quantity_pack > 0)
                        {{ $requisition_item->quantity_pack }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="text-end tdder2">
                        @if($requisition_item->quantity_pack > 0)
                        {{ number_format($requisition_item->item->per_pack, 2) }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="text-end tdder2">
                        @if($requisition_item->quantity_pack > 0)
                        {{ number_format($requisition_item->item->per_pack * $requisition_item->quantity_pack, 2) }}
                        @php $total_pack += $requisition_item->item->per_pack * $requisition_item->quantity_pack; @endphp
                        @else
                        -
                        @endif
                    </td> -->
                    <!-- <td class="text-center" style="background-color:rgb(247, 237, 182);">
                        @if($requisition_item->check_item == '1')
                        <i class="fa-solid fa-check text-success ms-2 check-icon"></i>
                        @endif
                    </td> -->
                    <!-- <script>
                        $(document).ready(function() {
                            $('#item_{{ $requisition_item->item_id }}').on('change', function() {
                                if ($(this).is(':checked')) {
                                    $('#check_icon_{{ $requisition_item->item_id }}').show();
                                } else {
                                    $('#check_icon_{{ $requisition_item->item_id }}').hide();
                                }
                            });
                        });
                    </script> -->
                </tr>
                @endforeach
                <tr style="background: #fff8dc;" class="tdder3">
                    <td colspan="4" class="text-end" style="background-color:rgb(247, 237, 182);">ราคารวมทั้งหมด</td>
                    <td class="fw-bold tdder4"  style="background-color:rgb(247, 237, 182);">
                        {{ number_format($requisition->total_price, 2) }} บาท
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="font1">
            หมายเหตุ : {{ $requisition->remarks ?? "....................." }}
        </div>

        <!-- <div align="center">
            อนุมัติโดย: {{ $requisition->approve_user->name ?? "............................................" }} <br>
            วันที่อนุมัติ:
            {{
                $requisition->approve_date 
                    ? \Carbon\Carbon::parse($requisition->approve_date)->locale('th')->translatedFormat('d F Y') 
                    : "............................." 
            }}
        </div> -->

        <table class="tdder1" style="width: 100%;">
            <tr>
                <td style="width: 50%;" class="font1">
                    อนุมัติโดย: {{ $requisition->approve_user->name ?? "............................................" }} <br>
                        วันที่:
                        {{
                        $requisition->approve_date 
                        ? \Carbon\Carbon::parse($requisition->approve_date)->locale('th')->translatedFormat('d F Y') 
                        : "............................................." 
                    }}
                    <br>
                    @if($requisition->approve_status == '1')
                    <span style="color:green;">หมายเหตุ: {{ $requisition->approve_comment ?? "............................................" }}</span> <br>
                    @elseif($requisition->approve_status == '2')
                    <span style="color: red;">หมายเหตุ: {{ $requisition->approve_comment ?? "............................................" }}</span> <br>
                    @else
                    <span>หมายเหตุ: {{ $requisition->approve_comment ?? "............................................" }}</span> <br>
                    @endif
                </td>
                <td style="width: 50%;" class="font1">
                    จัดเตรียมของโดย: {{ $requisition->packing_staff->name ?? "............................................" }} <br>
                    วันที่:
                    {{
                $requisition->packing_staff_date 
                    ? \Carbon\Carbon::parse($requisition->packing_staff_date)->locale('th')->translatedFormat('d F Y') 
                    : "............................................." 
            }}
                    <br>
                    @if($requisition->packing_staff_status == '1')
                    <span style="color:green;">หมายเหตุ: {{ $requisition->packing_staff_comment ?? "............................................" }}</span> <br>
                    @elseif($requisition->packing_staff_status == '2')
                    <span style="color: red;">หมายเหตุ: {{ $requisition->packing_staff_comment ?? "............................................" }}</span> <br>
                    @else
                    <span>หมายเหตุ: {{ $requisition->packing_staff_comment ?? "............................................" }}</span> <br>
                    @endif
                </td>
            </tr>
        </table>

    </div>
</body>

</html>