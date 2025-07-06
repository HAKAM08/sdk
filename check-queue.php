<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get the database connection
$db = $app->make('db');

// Check for pending jobs
$pendingJobs = $db->table('jobs')->count();
echo "Pending jobs in queue: {$pendingJobs}\n";

// Check for failed jobs
$failedJobs = $db->table('failed_jobs')->count();
echo "Failed jobs: {$failedJobs}\n";

if ($failedJobs > 0) {
    echo "\nFailed job details:\n";
    $failedJobDetails = $db->table('failed_jobs')
        ->select(['id', 'failed_at', 'exception'])
        ->orderBy('failed_at', 'desc')
        ->get();
    
    foreach ($failedJobDetails as $job) {
        echo "ID: {$job->id}, Failed at: {$job->failed_at}\n";
        echo "Exception: " . substr($job->exception, 0, 200) . "...\n\n";
    }
}

// Check if queue worker is running
if (PHP_OS_FAMILY === 'Windows') {
    exec('tasklist /FI "IMAGENAME eq php.exe" /FO CSV', $output);
    $workerRunning = false;
    foreach ($output as $line) {
        if (strpos($line, 'php.exe') !== false && strpos($line, 'queue:work') !== false) {
            $workerRunning = true;
            break;
        }
    }
} else {
    exec('ps aux | grep "[q]ueue:work"', $output);
    $workerRunning = !empty($output);
}

echo "Queue worker running: " . ($workerRunning ? "Yes" : "No") . "\n";

if (!$workerRunning) {
    echo "\nTo start the queue worker, run: php artisan queue:work\n";
}