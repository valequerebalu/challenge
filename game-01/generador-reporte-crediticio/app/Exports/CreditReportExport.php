<?php

namespace App\Exports;

use App\Repositories\CreditReportRepository;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreditReportExport implements FromQuery, WithMapping, WithHeadings, WithChunkReading, ShouldQueue
{
    use Exportable;

    protected $from;
    protected $to;

    /**
     * @param string $from Fecha de inicio (YYYY-MM-DD)
     * @param string $to   Fecha de fin (YYYY-MM-DD)
     */
    public function __construct(string $from, string $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Usamos el Repository con UNION ALL para obtener la consulta base.
     * Al ser FromQuery, la librería no ejecuta el ->get() inmediatamente,
     * sino que lo maneja por trozos (chunks).
     */
    public function query()
    {
        return (new CreditReportRepository())->getExportQuery($this->from, $this->to);
    }

    /**
     * Encabezados del archivo XLSX (Los 15 campos solicitados)
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nombre Completo',
            'DNI',
            'Email',
            'Teléfono',
            'Compañía',
            'Tipo de deuda',
            'Situación',
            'Atraso',
            'Entidad',
            'Monto total',
            'Línea total',
            'Línea usada',
            'Reporte subido el',
            'Estado'
        ];
    }

    /**
     * Mapeo de cada fila. Aquí puedes dar formato final a los datos.
     * $row es un objeto que contiene los resultados de la consulta SQL.
     */
    public function map($row): array
    {
        return [
            $row->report_id,
            $row->full_name,
            $row->document,
            $row->email,
            $row->phone,
            $row->company,
            $row->debt_type,
            $row->situation ?: 'N/A',
            $row->ed ?: 0,
            $row->company, // Entidad asociada
            number_format($row->total_amount, 2, '.', ''),
            number_format($row->total_line ?? 0, 2, '.', ''),
            number_format($row->used_line ?? 0, 2, '.', ''),
            $row->report_created_at,
            $row->general_status
        ];
    }

    /**
     * Tamaño del trozo para la lectura. 
     * Es vital para NO agotar la memoria RAM con millones de registros.
     */
    public function chunkSize(): int
    {
        return 5000;
    }
}