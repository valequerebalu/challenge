<?php
namespace App\Services;

use App\Exports\CreditReportExport;
use Maatwebsite\Excel\Facades\Excel;

class CreditReportService

{
    public function generateExcelReport($from, $to)
    {
        $fileName = 'reports/credit_' . now()->timestamp . '.xlsx';

        // Usamos store() para que se procese en segundo plano (ShouldQueue en el Export)
        // Esto guarda el archivo en el disco (o S3) sin bloquear al usuario
        return Excel::queue(new CreditReportExport($from, $to), $fileName, 'public');
    }

    
}