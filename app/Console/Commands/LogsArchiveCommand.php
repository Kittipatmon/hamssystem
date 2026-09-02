<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LogsArchiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive activity logs from previous years into monthly CSVs inside a yearly Zip file, and enforce 3-year retention.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting log archiving process...');

        $currentYear = (int) date('Y');
        $archivePath = storage_path('app/activity-logs-archive');

        if (!is_dir($archivePath)) {
            mkdir($archivePath, 0755, true);
        }

        // 1. Find all distinct years in activity_log table that are less than current year
        $yearsToArchive = \Spatie\Activitylog\Models\Activity::selectRaw('YEAR(created_at) as year')
            ->whereYear('created_at', '<', $currentYear)
            ->groupBy('year')
            ->pluck('year');

        foreach ($yearsToArchive as $year) {
            $this->info("Archiving logs for year: {$year}");

            $zipFileName = $archivePath . "/logs-{$year}.zip";
            $zip = new \ZipArchive();

            if ($zip->open($zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
                // Generate CSV for each month
                for ($month = 1; $month <= 12; $month++) {
                    $logs = \Spatie\Activitylog\Models\Activity::whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->get();

                    if ($logs->count() > 0) {
                        $csvFileName = "logs-{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . ".csv";
                        $tempCsvPath = storage_path("app/temp-{$csvFileName}");
                        
                        $file = fopen($tempCsvPath, 'w');
                        // Add BOM for UTF-8 Excel compatibility
                        fputs($file, "\xEF\xBB\xBF");
                        
                        // Headers
                        fputcsv($file, ['ID', 'Log Name', 'Description', 'Subject Type', 'Subject ID', 'Event', 'Causer Type', 'Causer ID', 'Properties', 'Created At']);

                        foreach ($logs as $log) {
                            fputcsv($file, [
                                $log->id,
                                $log->log_name,
                                $log->description,
                                $log->subject_type,
                                $log->subject_id,
                                $log->event,
                                $log->causer_type,
                                $log->causer_id,
                                json_encode($log->properties, JSON_UNESCAPED_UNICODE),
                                $log->created_at
                            ]);
                        }
                        fclose($file);

                        $zip->addFile($tempCsvPath, $csvFileName);
                    }
                }

                $zip->close();
                $this->info("Created zip archive: {$zipFileName}");

                // Clean up temp CSVs
                for ($month = 1; $month <= 12; $month++) {
                    $csvFileName = "logs-{$year}-" . str_pad($month, 2, '0', STR_PAD_LEFT) . ".csv";
                    $tempCsvPath = storage_path("app/temp-{$csvFileName}");
                    if (file_exists($tempCsvPath)) {
                        unlink($tempCsvPath);
                    }
                }

                // Delete records from database
                $deletedCount = \Spatie\Activitylog\Models\Activity::whereYear('created_at', $year)->delete();
                $this->info("Deleted {$deletedCount} records for year {$year} from database.");

            } else {
                $this->error("Failed to create zip file: {$zipFileName}");
            }
        }

        // 2. Enforce 3-year retention policy for zip files
        $this->info('Checking retention policy (keeping 3 years)...');
        $files = glob($archivePath . '/logs-*.zip');
        foreach ($files as $file) {
            $filename = basename($file);
            if (preg_match('/logs-(\d{4})\.zip/', $filename, $matches)) {
                $fileYear = (int) $matches[1];
                if ($currentYear - $fileYear > 3) {
                    unlink($file);
                    $this->info("Deleted old archive: {$filename}");
                }
            }
        }

        $this->info('Log archiving process completed successfully.');
    }
}
