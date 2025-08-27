<?php

namespace App\Console\Commands;

use App\Jobs\ComputeStatistics as ComputeStatisticsJob;
use Illuminate\Console\Command;

class ComputeStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:compute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compute search statistics';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ComputeStatisticsJob::dispatch();
        $this->info('Statistics computed successfully.');
    }
}
