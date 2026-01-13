<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCreditCard extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla (opcional, pero explícito)
     */
    protected $table = 'report_credit_cards';

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'subscription_report_id',
        'bank',
        'currency',
        'line',
        'used',
    ];

    /**
     * Relación: una tarjeta de crédito pertenece a un reporte de suscripción
     */
    public function subscriptionReport()
    {
        return $this->belongsTo(SubscriptionReport::class);
    }
}
