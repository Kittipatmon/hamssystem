<?php

namespace App\Mail;

use App\Models\News;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsOutlookNotification extends Mailable
{
    use Queueable, SerializesModels;

    public News $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function build(): self
    {
        $subjectPrefix = (string) env('OUTLOOK_NOTIFY_SUBJECT_PREFIX', '[HAMS]');
        $subject = trim($subjectPrefix . ' ข่าวสารใหม่: ' . ($this->news->title ?? ''));
        $published = $this->news->published_date ? (string) $this->news->published_date : null;
        $content = (string) ($this->news->content ?? '');
        // Split content by blank lines or new lines into paragraphs, trimming empty ones
        $parts = preg_split('/\R+/', $content);
        $contentParagraphs = array_values(array_filter(array_map('trim', $parts ?? [])));

        $relativePaths = $this->normalizeImagePaths($this->news->image_path);
        $absolutePaths = array_map(function ($p) {
            return asset(ltrim($p, '/'));
        }, $relativePaths);
        // ใช้เฉพาะรูปแรก
        $absolutePaths = array_slice($absolutePaths, 0, 1);

        $detailUrl = route('datamanage.news.detail', ['news' => $this->news->news_id ]);

        return $this->subject($subject)
            ->view('emails.news_outlook_notification', [
                'news' => $this->news,
                'published' => $published,
                'contentParagraphs' => $contentParagraphs,
                'absolutePaths' => $absolutePaths,
                'detailUrl' => $detailUrl,
            ]);
    }

    /**
     * Normalize image_path field to array of strings.
     * Accepts JSON array, comma-separated string, or a single path string.
     */
    private function normalizeImagePaths($imagePath): array
    {
        if (empty($imagePath)) { return []; }
        if (is_array($imagePath)) { return $imagePath; }
        $decoded = json_decode($imagePath, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return array_values(array_filter(array_map('trim', $decoded)));
        }
        if (is_string($imagePath) && strpos($imagePath, ',') !== false) {
            return array_values(array_filter(array_map('trim', explode(',', $imagePath))));
        }
        if (is_string($imagePath)) { return [trim($imagePath)]; }
        return [];
    }
}
