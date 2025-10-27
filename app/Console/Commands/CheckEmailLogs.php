<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckEmailLogs extends Command
{
    protected $signature = 'email:check {--lines=20 : Number of recent log lines to show}';
    protected $description = 'Check recent email logs for success/failure status';

    public function handle()
    {
        $lines = $this->option('lines');
        $logFile = storage_path('logs/laravel.log');

        if (!File::exists($logFile)) {
            $this->error('Log file not found: ' . $logFile);
            return 1;
        }

        $this->info("Checking last {$lines} email-related log entries...\n");

        // Read the log file and filter for email-related entries
        $logContent = File::get($logFile);
        $logLines = explode("\n", $logContent);

        $emailLines = array_filter($logLines, function ($line) {
            return strpos($line, 'CMail') !== false ||
                strpos($line, 'email') !== false ||
                strpos($line, 'SMTP') !== false;
        });

        $recentEmailLines = array_slice($emailLines, -$lines);

        if (empty($recentEmailLines)) {
            $this->warn('No email-related log entries found.');
            return 0;
        }

        foreach ($recentEmailLines as $line) {
            if (strpos($line, 'ERROR') !== false) {
                $this->error($line);
            } elseif (strpos($line, 'INFO') !== false) {
                $this->info($line);
            } else {
                $this->line($line);
            }
        }

        $this->newLine();
        $this->info('Email log check completed.');

        return 0;
    }
}
