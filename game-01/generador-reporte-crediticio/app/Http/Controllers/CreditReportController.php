<?php
namespace App\Http\Controllers;

use App\Http\Requests\CreditReportRequest;
use App\Http\Resources\ReportStatusResource;
use App\Services\CreditReportService;

class CreditReportController extends Controller
{
    public function export(CreditReportRequest $request, CreditReportService $service)
    {
        // 1. Obtenemos datos validados
        $data = $request->validated();

        // 2. Delegamos al servicio la lógica pesada
        $service->generateExcelReport($data['from'], $data['to']);

        // 3. Devolvemos una respuesta profesional usando un Resource
        // El resource dirá algo como: { "status": "processing", "job_id": 123 }
        return new ReportStatusResource($data);
    }
}