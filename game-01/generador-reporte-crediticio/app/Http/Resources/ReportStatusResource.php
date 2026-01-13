<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportStatusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'message' => 'El reporte masivo ha sido encolado exitosamente.',
            'status' => 'pending',
            'requested_range' => [
                'start' => $this->resource['from'] ?? $request->from,
                'end' => $this->resource['to'] ?? $request->to,
            ],
            'info' => 'Recibirás una notificación cuando el archivo esté listo para descargar.'
        ];
    }
}
