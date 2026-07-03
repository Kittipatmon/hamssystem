<?php
$html = '<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<!--[if gte mso 9]>
<xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Summary 2026</x:Name>
    <x:WorksheetSource HRef="summary.htm"/>
   </x:ExcelWorksheet>
   <x:ExcelWorksheet>
    <x:Name>Jan 2026</x:Name>
    <x:WorksheetSource HRef="jan.htm"/>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
 </x:ExcelWorkbook>
</xml>
<![endif]-->
</head>
<body>
</body>
</html>';
file_put_contents('public/test_multi.xls', $html);
file_put_contents('public/summary.htm', '<html><body><table><tr><td>Summary</td></tr></table></body></html>');
file_put_contents('public/jan.htm', '<html><body><table><tr><td>Jan</td></tr></table></body></html>');
echo "Created test_multi.xls\n";
