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
        Schema::create('report_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_report_id')->constrained('subscription_reports')->onDelete('cascade');
            $table->string('bank', 100);
            $table->enum('status', ['NOR', 'CPP', 'DEF', 'PER']); // NOR, CPP, DEF, etc.
            $table->string('currency', 3)->default('PEN');
            $table->decimal('amount', 12, 2);
            $table->integer('expiration_days')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Índice para mejorar el rendimiento al filtrar por banco o estado
            $table->index('subscription_report_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_loans');
    }
};
