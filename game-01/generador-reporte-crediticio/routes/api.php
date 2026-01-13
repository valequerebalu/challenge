<?php
use App\Http\Controllers\CreditReportController;
use Illuminate\Support\Facades\Route;

Route::post('/reports/export', [CreditReportController::class, 'export']);