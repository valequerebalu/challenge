<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.s
     */
    public function up(): void
    {
        Schema::create('subscription_reports', function (Blueprint $table) {
            $table->id();
            // Relación con subscriptions. Si se borra el usuario, se borran sus reportes (cascade)
            $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');
            $table->string('period', 7); // Formato YYYY-MM
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            // Índice compuesto para evitar duplicados de reportes en el mismo periodo para el mismo usuario
            // y acelerar las búsquedas.
            $table->index(['subscription_id', 'period']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_reports');
    }
};
