<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionReport extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla (opcional, pero explícito)
     */
    protected $table = 'subscription_reports';

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'subscription_id',
        'period',
    ];

    /**
     * Relación: un reporte pertenece a una suscripción
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Relación: un reporte tiene muchos préstamos
     */
    public function loans()
    {
        return $this->hasMany(ReportLoan::class);
    }

    /**
     * Relación: un reporte tiene muchas deudas adicionales
     */
    public function otherDebts()
    {
        return $this->hasMany(ReportOtherDebt::class);
    }

    /**
     * Relación: un reporte tiene muchas tarjetas de crédito
     */
    public function creditCards()
    {
        return $this->hasMany(ReportCreditCard::class);
    }
}
