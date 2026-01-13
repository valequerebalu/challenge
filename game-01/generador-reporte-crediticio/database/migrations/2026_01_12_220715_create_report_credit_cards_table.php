<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_credit_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_report_id')->constrained('subscription_reports')->onDelete('cascade');
            $table->string('bank', 100);
            $table->string('currency', 3)->default('PEN');
            $table->decimal('line', 12, 2); // Línea de crédito total
            $table->decimal('used', 12, 2); // Línea utilizada
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('subscription_report_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_credit_cards');
    }
};
