<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportOtherDebt extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla (opcional, pero explícito)
     */
    protected $table = 'report_other_debts';

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'subscription_report_id',
        'entity',
        'currency',
        'amount',
        'expiration_days',
    ];

    /**
     * Relación: una deuda adicional pertenece a un reporte de suscripción
     */
    public function subscriptionReport()
    {
        return $this->belongsTo(SubscriptionReport::class);
    }
}
