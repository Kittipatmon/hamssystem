<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<?php echo '<?mso-application progid="Excel.Sheet"?>'."\n"; ?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Tahoma" x:Family="Swiss" ss:Size="10" ss:Color="#000000"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="Title">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Tahoma" x:Family="Swiss" ss:Size="12" ss:Bold="1"/>
  </Style>
  <Style ss:ID="Header">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Tahoma" x:Family="Swiss" ss:Size="10" ss:Bold="1"/>
   <Interior ss:Color="#F8FAFC" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="HeaderTotal">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Tahoma" x:Family="Swiss" ss:Size="10" ss:Bold="1"/>
   <Interior ss:Color="#FEF3C7" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="CellCenter">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders>
  </Style>
  <Style ss:ID="CellLeft">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders>
  </Style>
  <Style ss:ID="TotalCell">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders>
   <Font ss:FontName="Tahoma" x:Family="Swiss" ss:Size="10" ss:Bold="1" ss:Color="#B45309"/>
   <Interior ss:Color="#FFFBEB" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="StockRed">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders>
   <Font ss:FontName="Tahoma" x:Family="Swiss" ss:Size="10" ss:Bold="1" ss:Color="#B91C1C"/>
   <Interior ss:Color="#FEE2E2" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="StockOrange">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders>
   <Font ss:FontName="Tahoma" x:Family="Swiss" ss:Size="10" ss:Bold="1" ss:Color="#C2410C"/>
   <Interior ss:Color="#FFEDD5" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="StockGreen">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders>
   <Font ss:FontName="Tahoma" x:Family="Swiss" ss:Size="10" ss:Bold="1" ss:Color="#15803D"/>
   <Interior ss:Color="#DCFCE3" ss:Pattern="Solid"/>
  </Style>
 </Styles>

 @php
    $monthsLabel = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    $currentMonth = date('n');
 @endphp

 <!-- Summary Sheet -->
 <Worksheet ss:Name="บันทึกรายการสต็อกประจำปี_{{ $year }}">
  <Table>
   <Column ss:Width="50"/>
   <Column ss:Width="80"/>
   <Column ss:Width="100"/>
   <Column ss:Width="200"/>
   @for($i=1; $i<=12; $i++)
    <Column ss:Width="50"/>
   @endfor
   <Column ss:Width="80"/>
   <Column ss:Width="80"/>
   <Column ss:Width="100"/>
   <Column ss:Width="80"/>
   <Column ss:Width="80"/>
   <Row ss:Height="25">
    <Cell ss:MergeAcross="20" ss:StyleID="Title"><Data ss:Type="String">บันทึกรายการสต็อกประจำปี {{ $year }}</Data></Cell>
   </Row>
   <Row>
    <Cell ss:StyleID="Header"><Data ss:Type="String">ลำดับ</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">รหัสพัสดุ</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">หมวดหมู่</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">ชื่อและคำอธิบายอุปกรณ์</Data></Cell>
    @foreach($monthsLabel as $m)
        <Cell ss:StyleID="Header"><Data ss:Type="String">{{ $m }}</Data></Cell>
    @endforeach
    <Cell ss:StyleID="HeaderTotal"><Data ss:Type="String">รวมเบิกทั้งปี</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">ราคา/หน่วย</Data></Cell>
    <Cell ss:StyleID="HeaderTotal"><Data ss:Type="String">มูลค่ารวม (บาท)</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">จำนวนคงคลัง</Data></Cell>
    <Cell ss:StyleID="Header"><Data ss:Type="String">สถานะสต็อก</Data></Cell>
   </Row>

   @php $grandTotalYear = 0; @endphp
   @foreach($items as $item)
    @php
        $totalWithdrawn = 0;
        if ($item->quantity == 0) {
            $styleStock = 'StockRed';
            $statusText = 'ของหมด';
        } elseif ($item->quantity <= 5) {
            $styleStock = 'StockOrange';
            $statusText = 'ใกล้หมด';
        } else {
            $styleStock = 'StockGreen';
            $statusText = 'มีพัสดุ';
        }
    @endphp
    <Row>
     <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $loop->iteration }}</Data></Cell>
     <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $item->item_code }}</Data></Cell>
     <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $item->items_type ? $item->items_type->name : 'General' }}</Data></Cell>
     <Cell ss:StyleID="CellLeft"><Data ss:Type="String">{{ $item->name }}</Data></Cell>
     @for($i = 1; $i <= 12; $i++)
        @php
            $withdrawn = $withdrawals[$item->item_id][$i] ?? 0;
            $totalWithdrawn += $withdrawn;
        @endphp
        <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $withdrawn > 0 ? number_format($withdrawn) : '-' }}</Data></Cell>
     @endfor
     <Cell ss:StyleID="TotalCell"><Data ss:Type="String">{{ $totalWithdrawn > 0 ? number_format($totalWithdrawn) : '-' }}</Data></Cell>
     <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ number_format($item->per_unit, 2) }}</Data></Cell>
     @php $grandTotalYear += ($totalWithdrawn * $item->per_unit); @endphp
     <Cell ss:StyleID="TotalCell"><Data ss:Type="String">{{ $totalWithdrawn > 0 ? number_format($totalWithdrawn * $item->per_unit, 2) : '-' }}</Data></Cell>
     <Cell ss:StyleID="{{ $styleStock }}"><Data ss:Type="String">{{ number_format($item->quantity) }}</Data></Cell>
     <Cell ss:StyleID="{{ $styleStock }}"><Data ss:Type="String">{{ $statusText }}</Data></Cell>
    </Row>
   @endforeach
   <Row ss:Height="20">
    <Cell ss:MergeAcross="17" ss:StyleID="HeaderTotal"><Data ss:Type="String">ยอดรวม</Data></Cell>
    <Cell ss:StyleID="TotalCell"><Data ss:Type="String">{{ number_format($grandTotalYear, 2) }}</Data></Cell>
    <Cell ss:MergeAcross="1" ss:StyleID="HeaderTotal"><Data ss:Type="String">บาท</Data></Cell>
   </Row>
  </Table>
 </Worksheet>

 <!-- Monthly Sheets -->
 @for($m = 1; $m <= $currentMonth; $m++)
    <Worksheet ss:Name="ประจำเดือน({{ str_pad($m, 2, '0', STR_PAD_LEFT) }}_{{ $year }})">
     <Table>
      <Column ss:Width="50"/>
      <Column ss:Width="80"/>
      <Column ss:Width="100"/>
      <Column ss:Width="200"/>
      <Column ss:Width="100"/>
      <Column ss:Width="100"/>
      <Column ss:Width="80"/>
      <Column ss:Width="100"/>
      <Column ss:Width="100"/>
      <Column ss:Width="100"/>
      
      <Row ss:Height="25">
       <Cell ss:MergeAcross="8" ss:StyleID="Title"><Data ss:Type="String">รายการสต็อกประจำเดือน {{ $monthsLabel[$m-1] }} {{ $year }}</Data></Cell>
      </Row>
      <Row>
       <Cell ss:StyleID="Header"><Data ss:Type="String">ลำดับ</Data></Cell>
       <Cell ss:StyleID="Header"><Data ss:Type="String">รหัสพัสดุ</Data></Cell>
       <Cell ss:StyleID="Header"><Data ss:Type="String">หมวดหมู่</Data></Cell>
       <Cell ss:StyleID="Header"><Data ss:Type="String">ชื่อและคำอธิบายอุปกรณ์</Data></Cell>
       <Cell ss:StyleID="Header"><Data ss:Type="String">จำนวนที่เบิกไป (เดือนนี้)</Data></Cell>
       <Cell ss:StyleID="Header"><Data ss:Type="String">ราคาต่อหน่วย (บาท)</Data></Cell>
       <Cell ss:StyleID="HeaderTotal"><Data ss:Type="String">มูลค่าการเบิก (บาท)</Data></Cell>
       <Cell ss:StyleID="Header"><Data ss:Type="String">จำนวนคงคลัง (ปัจจุบัน)</Data></Cell>
       <Cell ss:StyleID="Header"><Data ss:Type="String">สถานะสต็อก</Data></Cell>
      </Row>

      @php $grandTotalMonth = 0; @endphp
      @foreach($items as $item)
       @php
           $withdrawnThisMonth = $withdrawals[$item->item_id][$m] ?? 0;
           if ($item->quantity == 0) {
               $styleStock = 'StockRed';
               $statusText = 'ของหมด';
           } elseif ($item->quantity <= 5) {
               $styleStock = 'StockOrange';
               $statusText = 'ใกล้หมด';
           } else {
               $styleStock = 'StockGreen';
               $statusText = 'มีพัสดุ';
           }
       @endphp
       <Row>
        <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $loop->iteration }}</Data></Cell>
        <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $item->item_code }}</Data></Cell>
        <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $item->items_type ? $item->items_type->name : 'General' }}</Data></Cell>
        <Cell ss:StyleID="CellLeft"><Data ss:Type="String">{{ $item->name }}</Data></Cell>
        <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ $withdrawnThisMonth > 0 ? number_format($withdrawnThisMonth) : '-' }}</Data></Cell>
        <Cell ss:StyleID="CellCenter"><Data ss:Type="String">{{ number_format($item->per_unit, 2) }}</Data></Cell>
        @php $grandTotalMonth += ($withdrawnThisMonth * $item->per_unit); @endphp
        <Cell ss:StyleID="TotalCell"><Data ss:Type="String">{{ $withdrawnThisMonth > 0 ? number_format($withdrawnThisMonth * $item->per_unit, 2) : '-' }}</Data></Cell>
        <Cell ss:StyleID="{{ $styleStock }}"><Data ss:Type="String">{{ number_format($item->quantity) }}</Data></Cell>
        <Cell ss:StyleID="{{ $styleStock }}"><Data ss:Type="String">{{ $statusText }}</Data></Cell>
       </Row>
      @endforeach
      <Row ss:Height="20">
       <Cell ss:MergeAcross="5" ss:StyleID="HeaderTotal"><Data ss:Type="String">ยอดรวม</Data></Cell>
       <Cell ss:StyleID="TotalCell"><Data ss:Type="String">{{ number_format($grandTotalMonth, 2) }}</Data></Cell>
       <Cell ss:MergeAcross="1" ss:StyleID="HeaderTotal"><Data ss:Type="String">บาท</Data></Cell>
      </Row>
     </Table>
    </Worksheet>
 @endfor
</Workbook>
