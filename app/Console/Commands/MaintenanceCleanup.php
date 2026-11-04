<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MaintenanceCleanup extends Command
{
    protected $signature = 'app:maintenance-cleanup';

    protected $description = 'Clear cache and optimize application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cleanup...');

        Artisan::call('cache:clear');
        $this->info('Cache cleared');

        Artisan::call('config:clear');
        $this->info('Config cleared');

        Artisan::call('route:clear');
        $this->info('Routes cleared');

        Artisan::call('view:clear');
        $this->info('Views cleared');

        Artisan::call('optimize:clear');
        $this->info('Optimization cache cleared');

        Artisan::call('optimize');
        $this->info('Optimized');

        $this->info('Cleanup completed');

        return Command::SUCCESS;
    }
}
