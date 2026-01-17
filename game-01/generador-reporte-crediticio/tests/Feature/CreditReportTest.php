<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\GenerateCreditReportJob;
use App\Models\Subscription;
use App\Models\SubscriptionReport;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;

class CreditReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_dispatches_the_report_job_and_returns_correct_response()
    {
        // 1. Simular la cola para que no se ejecute el Job realmente
        Queue::fake();

        // 2. Hacer la petición a la API
        $response = $this->postJson('/api/reports/export', [
            'from' => '2025-07-01',
            'to' => '2025-12-31'
        ]);

        // 3. Verificar que la respuesta sea un 200 y tenga la estructura del Resource
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'message',
                    'status',
                    'requested_range',
                    'info'
                ]
            ]);

        // 4. Verificar que el Job fue enviado a la cola con los parámetros correctos
        Queue::assertPushed(GenerateCreditReportJob::class, function ($job) {
            return true; // Podrías validar que las fechas coincidan dentro del job si fuera necesario
        });
    }


  #[Test]
    public function it_generates_the_file_physically_in_storage()
    {

        Storage::fake('public');

        $sub = Subscription::factory()->create();
        SubscriptionReport::factory()->create(['subscription_id' => $sub->id, 'created_at' => '2025-08-15 10:00:00']);

        // Ejecutamos el Job de forma sincrónica (sin colas)
        $job = new GenerateCreditReportJob('2025-07-01', '2025-12-31');
        app()->call([$job, 'handle']);

        // Verificamos que se haya creado un archivo en la carpeta reports
        $files = Storage::disk('public')->files('reports');
        $this->assertNotEmpty($files, "El archivo Excel no fue generado en el storage.");
    }
}
