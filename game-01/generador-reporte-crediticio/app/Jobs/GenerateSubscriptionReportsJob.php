<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Subscription;
use App\Models\SubscriptionReport;
use Illuminate\Support\Facades\DB;

class GenerateSubscriptionReportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public int $tries = 3;
    /**
     * Create a new job instance.
     */
    public function __construct(
        protected int $count = 50
    ) {}

    public function handle(): void
    {
        set_time_limit(3600);
        DB::transaction(function () {
            Subscription::factory()
                ->count($this->count)
                ->has(
                    SubscriptionReport::factory()
                        ->count(rand(1, 3)) // Cada suscriptor tendrá entre 1 y 3 reportes
                        ->hasLoans(rand(2, 4)) // Cada reporte tendrá entre 2 y 4 préstamos
                        ->hasCreditCards(rand(1, 2)) // 1 o 2 tarjetas
                        ->hasOtherDebts(rand(0, 2))  // 0 a 2 otras deudas
                )
                ->create();
        });
    }
}
