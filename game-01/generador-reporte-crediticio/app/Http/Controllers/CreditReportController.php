<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditReportRequest;
use App\Http\Resources\ReportStatusResource;
use App\Services\CreditReportService;
use App\Jobs\GenerateCreditReportJob;

class CreditReportController extends Controller
{
    public function export(CreditReportRequest $request, CreditReportService $service)
    {

        $data = $request->validated();

        GenerateCreditReportJob::dispatch($data['from'], $data['to']);

        // Devolvemos una respuesta profesional usando un Resource

        return new ReportStatusResource($data);
    }
}
