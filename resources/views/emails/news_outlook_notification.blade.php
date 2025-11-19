<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title ?? 'News Title' }}</title>
    <style>
        body { margin:0; padding:0; background:#1f1f1f; font-family: Arial, Helvetica, sans-serif; color:#e6e6e6; -webkit-text-size-adjust:100%; }
        a { color:#4da3ff; }
        .wrap { width:100%; }
        .container { width:100%; max-width:720px; margin:0 auto; }
        .inner { padding:28px 40px 40px; }
        .heading { font-size:14px; font-weight:bold; letter-spacing:.5px; text-align:center; margin:0; padding:14px 0 12px; border-bottom:1px solid #444; }
        .title { font-size:18px; font-weight:bold; margin:0 0 14px; color:#fff; }
        .meta { font-size:12px; color:#bbb; margin:0 0 18px; }
        .paragraph { margin:0 0 12px; line-height:1.6; }
        .images-box { background:#fff; padding:8px 8px 4px; border:1px solid #d9d9d9; border-radius:2px; width:100%; max-width:400px; color:#111; }
        .images-box img { display:block; width:100%; height:auto; margin:0 0 8px; border-radius:2px; }
        .btn { display:inline-block; padding:11px 22px; background:#2563eb; color:#ffffff !important; text-decoration:none; font-weight:bold; font-size:14px; border-radius:4px; }
        .footer { font-size:11px; color:#aaa; margin:32px 0 8px; line-height:1.5; }
        .brand { color:#ff2d2d; font-weight:bold; }
        @media screen and (max-width:560px){ .inner { padding:24px 20px 32px; } .images-box{ max-width:100%; } }
    </style>
</head>
<body>
    <div class="wrap">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                    <div class="container">
                        <div class="heading">แผนกจัดการและบำรุงรักษาอาคาร (HAMS)</div>
                        <div class="inner" style="font-size:13px;">
                            <p class="title">{{ $news->title ?? 'No Title' }}</p>
                            <p class="meta">วันที่เผยแพร่: {{ isset($published) && $published ? \Carbon\Carbon::parse($published)->format('d M Y') : '-' }}</p>

                            @if(isset($contentParagraphs) && is_array($contentParagraphs))
                                @foreach($contentParagraphs as $para)
                                    <p class="paragraph">{{ $para }}</p>
                                @endforeach
                            @endif

                            @php
                                // --- Logic แก้ไข Path รูปภาพ + แก้ Error $message ---
                                $finalImageUrl = null;
                                $targetPath = null;

                                // 1. หา Path ของรูป
                                if(isset($absolutePaths) && is_array($absolutePaths) && count($absolutePaths)) {
                                    $targetPath = $absolutePaths[0];
                                } else {
                                    $raw = is_object($news) ? $news->image_path : null;
                                    if (is_string($raw) && strpos($raw, '[') !== false) {
                                        $decoded = json_decode($raw, true);
                                        if (is_array($decoded) && count($decoded) > 0) {
                                            $targetPath = $decoded[0];
                                        }
                                    } elseif (is_string($raw) && !empty($raw)) {
                                        $targetPath = $raw;
                                    }
                                }

                                // 2. Process รูปภาพ
                                if ($targetPath) {
                                    $p = (string)$targetPath;
                                    // ลบ Domain ทิ้ง (เผื่อมี)
                                    $p = preg_replace('/^(https?:)?\/\/+[^\/]+/i', '', $p);
                                    // ลบ / ตัวหน้าสุด และ public/
                                    $cleanPath = ltrim($p, '/');
                                    
                                    // Path จริงในเครื่อง
                                    $systemPath = public_path($cleanPath);

                                    if (file_exists($systemPath)) {
                                        // *** จุดที่แก้ Error: เช็คว่ามีตัวแปร $message ไหม ***
                                        if (isset($message)) {
                                            // กรณีส่งอีเมลจริง -> Embed
                                            $finalImageUrl = $message->embed($systemPath);
                                        } else {
                                            // กรณี Preview หน้าเว็บ -> ใช้ asset() ปกติ
                                            $finalImageUrl = asset($cleanPath);
                                        }
                                    }
                                }

                                // 3. Fallback
                                if (!$finalImageUrl) {
                                    $fallbackPath = public_path('images/welcome/news1.jpg');
                                    $fallbackUrlPath = 'images/welcome/news1.jpg';
                                    
                                    if (file_exists($fallbackPath)) {
                                        if (isset($message)) {
                                            $finalImageUrl = $message->embed($fallbackPath);
                                        } else {
                                            $finalImageUrl = asset($fallbackUrlPath);
                                        }
                                    }
                                }
                            @endphp

                            {{-- แสดงผลรูปภาพ --}}
                            @if($finalImageUrl)
                                <div style="margin:18px 0 6px;">
                                    <div class="images-box">
                                        <img src="{{ $finalImageUrl }}" alt="ภาพประกอบข่าว" style="width:100%; max-width:100%; height:auto;">
                                    </div>
                                </div>
                            @endif

                            <p style="margin:26px 0 8px;">
                                <a href="{{ $detailUrl ?? '#' }}" class="btn">ดูรายละเอียด</a>
                            </p>

                            <div class="footer">
                                <div>อีเมลนี้ถูกส่งโดยระบบ HAMS</div>
                                <div style="margin-top:14px;">
                                    Thank you and Regards,<br>
                                    แผนกจัดการและบำรุงรักษาอาคาร<br><br>
                                    Kumwell Corporation PLC. (Head Office)<br>
                                    358 Liang Muang Nonthaburi Road, Bangkrasor, Muang, Nonthaburi 11000<br>
                                    TEL: (662) 954-3455 FAX:(662) 591-7891<br>
                                    Website : www.kumwell.com<br>
                                    <span class="brand">Kumwell</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>