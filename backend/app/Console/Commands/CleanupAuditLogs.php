<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;

class CleanupAuditLogs extends Command
{
    
    protected $signature = 'audit:cleanup {--days=90 : Number of days to keep audit logs}';

    
    protected $description = 'Clean up old audit log entries';

    
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Cleaning up audit logs older than {$days} days...");
        
        $deleted = AuditLog::where('created_at', '<', now()->subDays($days))->delete();
        
        $this->info("Deleted {$deleted} old audit log entries.");
        
        return Command::SUCCESS;
    }
}
