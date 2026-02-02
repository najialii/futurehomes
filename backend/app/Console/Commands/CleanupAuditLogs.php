<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;

class CleanupAuditLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:cleanup {--days=90 : Number of days to keep audit logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old audit log entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Cleaning up audit logs older than {$days} days...");
        
        $deleted = AuditLog::where('created_at', '<', now()->subDays($days))->delete();
        
        $this->info("Deleted {$deleted} old audit log entries.");
        
        return Command::SUCCESS;
    }
}
