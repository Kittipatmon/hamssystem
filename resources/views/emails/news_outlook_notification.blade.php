<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title ?? 'ข่าวสารจาก HAMS' }}</title>
    <style>
        body { margin:0; padding:0; background-color:#f4f7f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color:#334155; -webkit-text-size-adjust:100%; }
        table { border-collapse: collapse; }
        a { color:#ef4444; text-decoration: none; }
        .container { width:100%; max-width:600px; margin:20px auto; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.05); }
        .header { background-color:#ffffff; padding:24px; border-bottom:4px solid #ef4444; text-align:center; }
        .content { padding:32px 40px; }
        .news-title { font-size:24px; font-weight:800; color:#0f172a; margin:0 0 12px; line-height:1.3; }
        .news-meta { font-size:12px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:24px; }
        .news-paragraph { font-size:15px; line-height:1.7; color:#334155; margin-bottom:16px; }
        .image-container { margin:24px 0; border-radius:8px; overflow:hidden; border:1px solid #e2e8f0; }
        .news-image { width:100%; display:block; height:auto; }
        .footer { background-color:#f8fafc; padding:32px 40px; text-align:center; border-top:1px solid #e2e8f0; }
        .footer-text { font-size:12px; color:#94a3b8; line-height:1.6; }
        .button { display:inline-block; padding:14px 28px; background-color:#ef4444; color:#ffffff !important; font-weight:700; font-size:14px; border-radius:8px; text-transform:uppercase; letter-spacing:1px; transition: background-color 0.2s; }
        .brand-name { color:#ef4444; font-weight:800; font-size:20px; }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f4f7f9">
        <tr>
            <td align="center">
                <div class="container">
                    <!-- Header -->
                    <div class="header">
                        <span class="brand-name">KUMWELL <small style="color:#64748b; font-weight:400; font-size:12px;">HAMS SYSTEM</small></span>
                    </div>

                    <!-- Main Content -->
                    <div class="content">
                        <div class="news-meta">
                            วันที่เผยแพร่: {{ isset($published) && $published ? \Carbon\Carbon::parse($published)->format('d M Y') : '-' }}
                        </div>
                        
                        <h1 class="news-title">{{ $news->title ?? 'ข่าวสารใหม่' }}</h1>

                        @if(isset($contentParagraphs) && is_array($contentParagraphs))
                            @foreach($contentParagraphs as $para)
                                <p class="news-paragraph">{{ $para }}</p>
                            @endforeach
                        @endif

                        @php
                            $finalImageUrl = null;
                            $targetPath = null;

                            // Find the first image path
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

                            if ($targetPath) {
                                $cleanPath = ltrim(preg_replace('/^(https?:)?\/\/+[^\/]+/i', '', (string)$targetPath), '/');
                                $systemPath = public_path($cleanPath);

                                if (file_exists($systemPath)) {
                                    // Use CID embedding for SMTP, direct asset URL for Graph/Browser
                                    if (isset($message) && method_exists($message, 'embed')) {
                                        $finalImageUrl = $message->embed($systemPath);
                                    } else {
                                        $finalImageUrl = asset($cleanPath);
                                    }
                                }
                            }

                            // Fallback image if needed
                            if (!$finalImageUrl) {
                                $fallbackPath = public_path('images/welcome/news1.jpg');
                                if (file_exists($fallbackPath)) {
                                    if (isset($message) && method_exists($message, 'embed')) {
                                        $finalImageUrl = $message->embed($fallbackPath);
                                    } else {
                                        $finalImageUrl = asset('images/welcome/news1.jpg');
                                    }
                                }
                            }
                        @endphp

                        @if($finalImageUrl)
                            <div class="image-container">
                                <img src="{{ $finalImageUrl }}" alt="ข่าวประชาสัมพันธ์" class="news-image">
                            </div>
                        @endif

                        <div style="margin-top:32px; text-align:center;">
                            <a href="{{ $detailUrl ?? '#' }}" class="button">อ่านรายละเอียดเพิ่มเติม</a>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="footer">
                        <p class="footer-text">
                            <strong>Kumwell Corporation PLC. (Head Office)</strong><br>
                            358 Liang Muang Nonthaburi Road, Bangkrasor, Muang, Nonthaburi 11000<br>
                            TEL: (662) 954-3455 | Email: hams@kumwell.com<br>
                            <a href="http://www.kumwell.com">www.kumwell.com</a>
                        </p>
                        <div style="margin-top:20px; font-size:10px; color:#cbd5e1;">
                            อีเมลนี้ส่งโดยระบบอัตโนมัติ HAMS - ข้อมูล ณ วันที่ {{ now()->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>