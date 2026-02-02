<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\Service;
use App\Models\Project;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\Stat;
use Illuminate\Console\Command;

class CheckSeededData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:seeded-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check seeded data in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Seeded Data Summary ===');
        
        $this->info('Pages (' . Page::count() . '):');
        foreach (Page::all() as $page) {
            $status = $page->is_published ? 'published' : 'draft';
            $this->line("  - {$page->slug} - {$page->title} ({$status})");
        }
        
        $this->info('Services (' . Service::count() . '):');
        foreach (Service::all() as $service) {
            $status = $service->is_active ? 'active' : 'inactive';
            $this->line("  - {$service->title} ({$status})");
        }
        
        $this->info('Projects (' . Project::count() . '):');
        foreach (Project::all() as $project) {
            $this->line("  - {$project->name} ({$project->status})");
        }
        
        $this->info('Partners (' . Partner::count() . '):');
        foreach (Partner::all() as $partner) {
            $status = $partner->is_active ? 'active' : 'inactive';
            $this->line("  - {$partner->name} ({$status})");
        }
        
        $this->info('Testimonials (' . Testimonial::count() . '):');
        foreach (Testimonial::all() as $testimonial) {
            $this->line("  - {$testimonial->client_name} - Rating: {$testimonial->rating}/5 ({$testimonial->status})");
        }
        
        $this->info('Stats (' . Stat::count() . '):');
        foreach (Stat::all() as $stat) {
            $status = $stat->is_active ? 'active' : 'inactive';
            $this->line("  - {$stat->name}: {$stat->number} ({$status})");
        }
        
        $this->info('=== Data seeded successfully! ===');
    }
}
