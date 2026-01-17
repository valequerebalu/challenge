<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateSubscriptionReportsJob;

class RunStressTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:stress {users=500} {batchSize=50}';
    protected $description = 'Genera data masiva para probar el reporte de Excel';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $totalUsers = $this->argument('users');
        $batchSize = $this->argument('batchSize');
        $jobsCount = ceil($totalUsers / $batchSize);

        $this->info("Despachando {$jobsCount} jobs para crear {$totalUsers} usuarios...");

        for ($i = 0; $i < $jobsCount; $i++) {
            // Enviamos lotes pequeños a la cola
            GenerateSubscriptionReportsJob::dispatch($batchSize);
        }

        $this->info("¡Hecho! Revisa tu tabla de 'jobs' o corre 'php artisan queue:work'.");
    }
}
