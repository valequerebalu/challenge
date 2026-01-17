<?php

namespace App\Services;

use App\Repositories\CreditReportRepository;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Storage;

class CreditReportService

{
    protected CreditReportRepository $repository;

    public function __construct(CreditReportRepository $repository)
    {
        $this->repository = $repository;
    }

    public function generateExcelReport($from, $to)
    {
        $fileName = 'reports/credit_' . now()->timestamp . '.xlsx';


        Storage::disk('public')->makeDirectory('reports');

        $query = $this->repository->getExportQuery($from, $to);

        $tempPath = tempnam(sys_get_temp_dir(), 'excel');
        // Usamos cursor() para procesar 1 fila a la vez (Memoria constante)
        (new FastExcel($query->cursor()->getIterator()))->export($tempPath, function ($row) {
            return [
                'ID'                => $row->report_id,
                'Nombre Completo'   => $row->full_name,
                'DNI'               => $row->document,
                'Email'             => $row->email,
                'Teléfono'          => $row->phone,
                'Compañía'          => $row->company,
                'Tipo de deuda'     => $row->debt_type,
                'Situación'         => $row->situation ?: 'N/A',
                'Atraso'            => (int) $row->ed ?: 0,
                'Entidad'           => $row->company,
                'Monto total'       => (float) $row->total_amount,
                'Línea total'       => (float) $row->total_line ?? 0,
                'Línea usada'       => (float) $row->used_line ?? 0,
                'Reporte subido el' => $row->report_created_at,
                'Estado'            => $row->general_status,

            ];
        });
       Storage::disk('public')->put($fileName, fopen($tempPath, 'r+'));

        unlink($tempPath);

        return $fileName;
    }
}
