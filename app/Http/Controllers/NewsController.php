<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NewsOutlookNotification;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;

class NewsController extends Controller
{
    public function index()
    {
        // For data management, list all news (active and inactive) by publish date, then creation time
        $news = News::orderByDesc('published_date')
                    ->orderByDesc('created_at')
                    ->get();

        return view('datamanage.news.index', compact('news'));
    }

    public function create()
    {
        $news = new News([
            'is_active' => true,
            'published_date' => now()->toDateString(),
        ]);
        return view('datamanage.news.create', compact('news'));
    }

    public function store(StoreNewsRequest $request)
    {
        $data = $request->validated();

        // Handle multiple image uploads (images[]). Keep legacy 'image' for backward compatibility.
        $uploadedPaths = [];
        $destination = public_path('images/news');
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if (!$file) { continue; }
                $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($destination, $filename);
                $uploadedPaths[] = 'images/news/' . $filename;
            }
        } elseif ($request->hasFile('image')) {
            // Legacy single upload
            $file = $request->file('image');
            $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $uploadedPaths[] = 'images/news/' . $filename;
        }

        $data['image_path'] = !empty($uploadedPaths) ? json_encode($uploadedPaths) : null;

        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : false;
        $news = News::create($data);

        return redirect()->route('datamanage.news.index')->with('success', 'บันทึกข่าวสารเรียบร้อยแล้ว');
    }

    public function edit(News $news)
    {
        return view('datamanage.news.edit', compact('news'));
    }

    public function update(UpdateNewsRequest $request, News $news)
    {
        $data = $request->validated();
        $existingImages = $this->normalizeImagePaths($news->image_path);

        $destination = public_path('images/news');
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $uploadedPaths = [];

        if ($request->hasFile('images')) {
            // Remove old images
            foreach ($existingImages as $img) {
                $path = public_path(ltrim($img, '/'));
                if (is_file($path)) { @unlink($path); }
            }
            foreach ($request->file('images') as $file) {
                if (!$file) { continue; }
                $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($destination, $filename);
                $uploadedPaths[] = 'images/news/' . $filename;
            }
            $data['image_path'] = !empty($uploadedPaths) ? json_encode($uploadedPaths) : null;
        } elseif ($request->hasFile('image')) {
            // Remove old images
            foreach ($existingImages as $img) {
                $path = public_path(ltrim($img, '/'));
                if (is_file($path)) { @unlink($path); }
            }
            $file = $request->file('image');
            $filename = now()->format('YmdHis') . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($destination, $filename);
            $data['image_path'] = json_encode(['images/news/' . $filename]);
        } else {
            // No new uploads; keep existing images
            unset($data['image_path']);
        }

        $data['is_active'] = isset($data['is_active']) ? (bool)$data['is_active'] : false;

        $news->update($data);

        return redirect()->route('datamanage.news.index')->with('success', 'แก้ไขข่าวสารเรียบร้อยแล้ว');
    }

    public function destroy(News $news)
    {
        // Delete all image files if exist (supports multiple or single)
        $images = $this->normalizeImagePaths($news->image_path);
        foreach ($images as $img) {
            $path = public_path(ltrim($img, '/'));
            if (is_file($path)) { @unlink($path); }
        }

        $news->delete();

        return redirect()->route('datamanage.news.index')->with('success', 'ลบข่าวสารเรียบร้อยแล้ว');
    }

    public function detail($id)
    {
        $news = News::findOrFail($id);
        return view('datamanage.news.detail', compact('news'));
    }

    /**
     * Normalize image_path field to array of strings.
     * Accepts JSON array, comma-separated string, or a single path string.
     */
    private function normalizeImagePaths($imagePath): array
    {
        if (empty($imagePath)) { return []; }

        // If already an array
        if (is_array($imagePath)) { return $imagePath; }

        // Try JSON decode
        $decoded = json_decode($imagePath, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter(array_map('trim', $decoded)));
        }

        // Comma separated
        if (is_string($imagePath) && strpos($imagePath, ',') !== false) {
            return array_values(array_filter(array_map('trim', explode(',', $imagePath))));
        }

        // Single path string
        if (is_string($imagePath)) { return [trim($imagePath)]; }

        return [];
    }

    public function newsall(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $query = News::where('is_active', true);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('newto', 'like', "%{$search}%");
            });
        }

        $news = $query->orderByDesc('published_date')
                      ->orderByDesc('created_at')
                      ->get();

        return view('datamanage.news.newsall', compact('news'));
    }

    public function filterNews(Request $request)
    {
        $query = News::query()->where('is_active', true);

        if ($request->filled('start_date')) {
            $query->whereDate('published_date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('published_date', '<=', $request->input('end_date'));
        }

        $news = $query->orderByDesc('published_date')
                      ->orderByDesc('created_at')
                      ->get();

        return view('datamanage.news.newsall', compact('news'));
    }

    /**
     * Send Outlook email notification for a specific news item.
     */
    public function sendOutlook(Request $request, News $news)
    {
        // Collect extra emails from form (multi-select or comma separated text)
        $extra = $this->extractExtraEmails($request);

        if (!$this->isMicrosoftAuthenticated()) {
            session([
                'post_login_notify_news_id' => $news->id,
                'post_login_notify_emails' => $extra,
            ]);
            return redirect()->route('auth.microsoft.redirect');
        }
        return $this->sendOutlookMail($news, $extra);
    }

    /**
     * Continue sending after Microsoft login (GET route helper)
     */
    public function sendOutlookAfterLogin(Request $request, News $news)
    {
        if (!$this->isMicrosoftAuthenticated()) {
            session(['post_login_notify_news_id' => $news->id]);
            return redirect()->route('auth.microsoft.redirect');
        }
        // Retrieve any stored emails from session if coming from OAuth flow
        $extra = session('post_login_notify_emails', []);
        return $this->sendOutlookMail($news, is_array($extra) ? $extra : []);
    }

    private function isMicrosoftAuthenticated(): bool
    {
        return (bool) session()->has('ms_oauth.token');
    }

    private function sendOutlookMail(News $news, array $extra = [])
    {
        // Use only the emails selected (Select2 + manual input). No implicit base recipient.
        $to = array_values(array_unique($extra));
        $cc = [];
        $bcc = [];

        if (empty($to)) {
            return back()->with('error', 'กรุณาเลือกหรือกรอกอีเมลผู้รับอย่างน้อย 1 รายการ');
        }

        // If Microsoft OAuth token exists, try Graph first
        $ms = session('ms_oauth');
        if (is_array($ms) && !empty($ms['token'])) {
            $graphResult = $this->sendViaMicrosoftGraph($ms['token'], $ms['email'] ?? null, $news, $to, $cc, $bcc);
            if ($graphResult['ok']) {
                return back()->with('success', 'ส่งแจ้งเตือน Outlook (Graph) สำเร็จ');
            }
            // fall back if Graph failed
            Log::warning('Graph send failed, falling back to Laravel Mail', $graphResult);
        }

        if (config('mail.default') === 'log') {
            Log::warning('Mail default driver is log; email will not be delivered to Outlook.', [
                'news_id' => $news->id,
                'title' => $news->title,
            ]);
        }
        try {
            $mailable = new NewsOutlookNotification($news);
            $mailer = Mail::to($to);
            if ($cc) { $mailer->cc($cc); }
            if ($bcc) { $mailer->bcc($bcc); }
            $mailer->send($mailable);
            Log::info('News Outlook mail sent via Laravel Mail driver.', [
                'news_id' => $news->id,
                'title' => $news->title,
                'recipients' => $to,
            ]);
            dd($mailable);
            exit;

            // Clear any post-login stored emails
            session()->forget(['post_login_notify_news_id', 'post_login_notify_emails']);
            return back()->with('success', 'ส่งแจ้งเตือน Outlook สำเร็จ');
        } catch (\Throwable $e) {
            Log::error('Failed sending News Outlook mail (fallback driver)', [
                'news_id' => $news->id,
                'title' => $news->title,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'ไม่สามารถส่งอีเมลแจ้งเตือนได้: ' . $e->getMessage());
        }
    }

    /**
     * Extract additional recipient emails from request.
     */
    private function extractExtraEmails(Request $request): array
    {
        $rawArray = (array) $request->input('extra_emails', []);
        $text = (string) $request->input('extra_emails_text', '');

        if ($text !== '') {
            $parts = preg_split('/\s*,\s*/', $text);
            $rawArray = array_merge($rawArray, is_array($parts) ? $parts : []);
        }

        $clean = [];
        foreach ($rawArray as $em) {
            $em = strtolower(trim($em));
            if ($em === '') { continue; }
            if (filter_var($em, FILTER_VALIDATE_EMAIL)) {
                $clean[] = $em;
            }
        }
        return array_values(array_unique($clean));
    }

    /**
     * Send mail through Microsoft Graph API using access token.
     */
    private function sendViaMicrosoftGraph(string $accessToken, ?string $senderEmail, News $news, array $to, array $cc = [], array $bcc = []): array
    {
        $endpoint = 'https://graph.microsoft.com/v1.0/me/sendMail';
        if ($senderEmail) {
            // Using /users/{id | userPrincipalName}/sendMail allows explicit sender
            $endpoint = 'https://graph.microsoft.com/v1.0/users/' . urlencode($senderEmail) . '/sendMail';
        }
        $subjectPrefix = (string) env('OUTLOOK_NOTIFY_SUBJECT_PREFIX', '[HAMS]');
        $subject = trim($subjectPrefix . ' ข่าวสารใหม่: ' . ($news->title ?? ''));
        $detailUrl = route('datamanage.news.detail', ['news' => $news->news_id ]);

        $buildRecipients = function(array $emails) {
            return array_map(fn($e) => ['emailAddress' => ['address' => $e]], $emails);
        };

        // Prepare same variables as Mailable for consistent rendering
        $published = $news->published_date ? (string) $news->published_date : null;
        $content = (string) ($news->content ?? '');
        $parts = preg_split('/\R+/', $content);
        $contentParagraphs = array_values(array_filter(array_map('trim', $parts ?? [])));
        $relativePaths = $this->normalizeImagePaths($news->image_path);
        $absolutePaths = array_map(function ($p) { return asset(ltrim($p, '/')); }, $relativePaths);
        // จำกัดให้ส่งเฉพาะรูปแรก
        $absolutePaths = array_slice($absolutePaths, 0, 1);
        $contentHtml = view('emails.news_outlook_notification', [
            'news' => $news,
            'published' => $published,
            'contentParagraphs' => $contentParagraphs,
            'absolutePaths' => $absolutePaths,
            'detailUrl' => $detailUrl,
        ])->render();
        $payload = [
            'message' => [
                'subject' => $subject,
                'body' => [
                    'contentType' => 'HTML',
                    'content' => $contentHtml,
                ],
                'toRecipients' => $buildRecipients($to),
                'ccRecipients' => $buildRecipients($cc),
                'bccRecipients' => $buildRecipients($bcc),
            ],
            'saveToSentItems' => true,
        ];
        try {
            $client = new \GuzzleHttp\Client(['timeout' => 10]);
            $res = $client->post($endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);
            $code = $res->getStatusCode();
            Log::info('Graph sendMail response', ['status' => $code, 'news_id' => $news->id]);
            return ['ok' => $code >= 200 && $code < 300, 'status' => $code];
        } catch (\Throwable $e) {
            Log::error('Graph sendMail failed', [
                'error' => $e->getMessage(),
                'news_id' => $news->id,
            ]);
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }
}
// C:\xampp\htdocs\hamssystem\resources\views\datamanage\news\detail.blade.php