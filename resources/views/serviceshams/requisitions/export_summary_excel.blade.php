<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<?php echo '<?mso-application progid="Excel.Sheet"?>'."\n"; ?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Center"/>
   <Borders/>
   <Font ss:FontName="TH Sarabun New" ss:Size="16" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="HeaderTitle">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="TH Sarabun New" ss:Size="18" ss:Color="#000000" ss:Bold="1"/>
  </Style>
  <Style ss:ID="Header">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="TH Sarabun New" ss:Size="16" ss:Color="#000000" ss:Bold="1"/>
   <Interior ss:Color="#E2EFDA" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="HeaderTotal">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="TH Sarabun New" ss:Size="16" ss:Color="#000000" ss:Bold="1"/>
   <Interior ss:Color="#E2EFDA" ss:Pattern="Solid"/>
   <NumberFormat ss:Format="#,##0.00"/>
  </Style>
  <Style ss:ID="TotalRow">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="TH Sarabun New" ss:Size="16" ss:Color="#000000" ss:Bold="1"/>
   <Interior ss:Color="#FFD966" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="TotalRowNumber">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="TH Sarabun New" ss:Size="16" ss:Color="#000000" ss:Bold="1"/>
   <Interior ss:Color="#FFD966" ss:Pattern="Solid"/>
   <NumberFormat ss:Format="#,##0.00"/>
  </Style>
  <Style ss:ID="Data">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="TH Sarabun New" ss:Size="16" ss:Color="#000000"/>
  </Style>
  <Style ss:ID="DataCenter">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="TH Sarabun New" ss:Size="16" ss:Color="#000000"/>
  </Style>
  <Style ss:ID="DataNumber">
   <Alignment ss:Horizontal="Right" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="TH Sarabun New" ss:Size="16" ss:Color="#000000"/>
   <NumberFormat ss:Format="#,##0.00"/>
  </Style>
 </Styles>

 <Worksheet ss:Name="สรุปรายการเบิกอุปกรณ์">
  <Table>
   <Column ss:Width="50"/>
   <Column ss:Width="150"/>
   <Column ss:Width="100"/>
   <Column ss:Width="120"/>
   <Column ss:Width="100"/>
   <Column ss:Width="200"/>
   <Column ss:Width="80"/>
   <Column ss:Width="100"/>

   <Row ss:Height="25">
    <Cell ss:MergeAcross="7" ss:StyleID="HeaderTitle"><Data ss:Type="String">สรุปประวัติการเบิกอุปกรณ์</Data></Cell>
   </Row>
   <Row ss:Height="25">
    <Cell ss:MergeAcross="7" ss:StyleID="HeaderTitle"><Data ss:Type="String">ประจำเดือน {{ $monthRange }}</Data></Cell>
   </Row>
   <Row ss:Height="15"></Row>

   <Row ss:Height="25">
    <Cell ss:StyleID="Header"><Data ss:Type="String">ลำดับ</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">ชื่อ-สกุลผู้เบิก</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">จำนวนครั้งที่เบิก</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">มูลค่ารวม (บาท)</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">รหัสพัสดุ</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">ชื่ออุปกรณ์</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">จำนวน (ชิ้น)</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">วันที่เบิก</Data></Cell>
   </Row>

   @php
    $no = 1;
    $grandTotalCount = 0;
    $grandTotalPrice = 0;
   @endphp
   @foreach($summary as $row)
    @php
        $items = $row['items'];
        $itemsCount = count($items);
        $mergeDownAttr = $itemsCount > 1 ? ' ss:MergeDown="' . ($itemsCount - 1) . '"' : '';
        
        $grandTotalCount += $row['total_count'];
        $grandTotalPrice += $row['total_price'];
    @endphp
    
    @if($itemsCount > 0)
        @foreach($items as $index => $item)
            <Row>
             @if($index === 0)
                 <Cell ss:StyleID="DataCenter"{!! $mergeDownAttr !!}><Data ss:Type="Number">{{ $no++ }}</Data></Cell>
                 <Cell ss:StyleID="Data"{!! $mergeDownAttr !!}><Data ss:Type="String">คุณ{{ optional($row['user'])->fullname ?? 'ไม่ระบุตัวตน' }}</Data></Cell>
                 <Cell ss:StyleID="DataCenter"{!! $mergeDownAttr !!}><Data ss:Type="Number">{{ $row['total_count'] }}</Data></Cell>
                 <Cell ss:StyleID="DataNumber"{!! $mergeDownAttr !!}><Data ss:Type="Number">{{ $row['total_price'] }}</Data></Cell>
             @endif
             <Cell @if($index > 0) ss:Index="5" @endif ss:StyleID="DataCenter"><Data ss:Type="String">{{ $item['code'] }}</Data></Cell>
             <Cell ss:StyleID="Data"><Data ss:Type="String">{{ $item['name'] }}</Data></Cell>
             <Cell ss:StyleID="DataCenter"><Data ss:Type="Number">{{ $item['quantity'] }}</Data></Cell>
             <Cell ss:StyleID="DataCenter"><Data ss:Type="String">{{ $item['date'] }}</Data></Cell>
            </Row>
        @endforeach
    @else
        <Row>
             <Cell ss:StyleID="DataCenter"><Data ss:Type="Number">{{ $no++ }}</Data></Cell>
             <Cell ss:StyleID="Data"><Data ss:Type="String">คุณ{{ optional($row['user'])->fullname ?? 'ไม่ระบุตัวตน' }}</Data></Cell>
             <Cell ss:StyleID="DataCenter"><Data ss:Type="Number">{{ $row['total_count'] }}</Data></Cell>
             <Cell ss:StyleID="DataNumber"><Data ss:Type="Number">{{ $row['total_price'] }}</Data></Cell>
             <Cell ss:StyleID="DataCenter"><Data ss:Type="String">-</Data></Cell>
             <Cell ss:StyleID="Data"><Data ss:Type="String">-</Data></Cell>
             <Cell ss:StyleID="DataCenter"><Data ss:Type="Number">0</Data></Cell>
             <Cell ss:StyleID="DataCenter"><Data ss:Type="String">-</Data></Cell>
        </Row>
    @endif
   @endforeach
   
   <Row>
     <Cell ss:MergeAcross="1" ss:StyleID="TotalRow"><Data ss:Type="String">ยอดรวมทั้งหมด</Data></Cell>
     <Cell ss:StyleID="TotalRow"><Data ss:Type="Number">{{ $grandTotalCount }}</Data></Cell>
     <Cell ss:StyleID="TotalRowNumber"><Data ss:Type="Number">{{ $grandTotalPrice }}</Data></Cell>
     <Cell ss:MergeAcross="3" ss:StyleID="TotalRow"><Data ss:Type="String"></Data></Cell>
   </Row>
  </Table>
 </Worksheet>
</Workbook>
