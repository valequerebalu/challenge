<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\CreditReportService;
use Illuminate\Support\Facades\Log;

class GenerateCreditReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $from;
    protected $to;


    public $timeout = 1200;
    /**
     * Create a new job instance.
     */
    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Execute the job.
     */
  public function handle(CreditReportService $service)
    {
        Log::info("Iniciando Job de exportación masiva: {$this->from} al {$this->to}");
        
        // El Service ahora corre dentro del Worker de Laravel, no en el request de Postman
        $service->generateExcelReport($this->from, $this->to);

        Log::info("Job de exportación finalizado con éxito.");
    }
}
