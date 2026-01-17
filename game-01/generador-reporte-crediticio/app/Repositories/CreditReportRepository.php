<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Carbon\Carbon;

class CreditReportRepository
{
    public function getExportQuery($from, $to): Builder
    {

        $startDate = Carbon::parse($from)->startOfDay();
        $endDate = Carbon::parse($to)->endOfDay();
        // 1. Fragmento Préstamos
        $loans = DB::table('report_loans as rl')
            ->select(
                'rl.subscription_report_id as report_id',
                'rl.bank as company',
                DB::raw("'Préstamo' as debt_type"),
                'rl.status as situation',
                'rl.expiration_days as ed',
                'rl.bank as entidad',
                'rl.amount as total_amount',
                DB::raw('0 as total_line'),
                DB::raw('0 as used_line'),
                'rl.created_at'
            );

        // 2. Fragmento Tarjetas
        $cards = DB::table('report_credit_cards as rcc')
            ->select(
                'rcc.subscription_report_id as report_id',
                'rcc.bank as company',
                DB::raw("'Tarjeta de crédito' as debt_type"),
                DB::raw("'NOR' as situation"), // Tarjetas suelen ser NOR si están activas
                DB::raw('0 as ed'),
                'rcc.bank as entidad',
                'rcc.used as total_amount',
                'rcc.line as total_line',
                'rcc.used as used_line',
                'rcc.created_at'
            );

        // 3. Fragmento Otras Deudas
        $others = DB::table('report_other_debts as rod')
            ->select(
                'rod.subscription_report_id as report_id',
                'rod.entity as company',
                DB::raw("'Otra deuda' as debt_type"),
                DB::raw("'' as situation"),
                'rod.expiration_days as ed',
                'rod.entity as entidad',
                'rod.amount as total_amount',
                DB::raw('0 as total_line'),
                DB::raw('0 as used_line'),
                'rod.created_at'
            );

        // Unión de deudas
        $allDebts = $loans->unionAll($cards)->unionAll($others);

        // Consulta Final con los 15 campos
        return DB::table('subscriptions as s')
            ->join('subscription_reports as sr', 's.id', '=', 'sr.subscription_id')
            ->joinSub($allDebts, 'debts', 'sr.id', '=', 'debts.report_id')
            ->whereBetween('sr.created_at', [$startDate, $endDate])
            ->select([
                'debts.report_id',          // 1. ID Reporte
                's.full_name',              // 2. Nombre Completo
                's.document',               // 3. DNI
                's.email',                  // 4. Email
                's.phone',                  // 5. Teléfono
                'debts.company',            // 6. Compañía
                'debts.debt_type',          // 7. Tipo de deuda
                'debts.situation',          // 8. Situación
                'debts.ed',                 // 9. Atraso
                'debts.entidad',            // 10. Entidad
                'debts.total_amount',        // 11. Monto total
                'debts.total_line',        // 12. Línea total
                'debts.used_line',        // 13. Línea usada
                'sr.created_at as report_created_at', // 14. Reporte subido el
                DB::raw(
                    "
                    CASE 
                        WHEN debts.situation IN ('DEF', 'PER') THEN 'Crítico'
                        WHEN debts.ed > 30 THEN 'En riesgo'
                        WHEN debts.total_line > 0 AND (debts.used_line / debts.total_line) >= 0.8 THEN 'Sobre-endeudado'
                        ELSE 'Normal'
                    END as general_status" // 15. Estado
                )
            ]);
    }
}
