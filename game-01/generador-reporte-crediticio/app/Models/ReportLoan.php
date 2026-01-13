<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportLoan extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla (opcional, pero explícito)
     */
    protected $table = 'report_loans';

    protected $fillable = [
        'subscription_report_id',
        'bank',
        'status',
        'currency',
        'amount',
        'expiration_days',
    ];

    /**
     * Relación: un préstamo pertenece a un reporte de suscripción
     */
    public function subscriptionReport()
    {
        return $this->belongsTo(SubscriptionReport::class);
    }
}
