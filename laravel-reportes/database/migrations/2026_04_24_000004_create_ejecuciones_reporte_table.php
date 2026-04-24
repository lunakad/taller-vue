<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ejecuciones_reporte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporte_id')->constrained('reportes');
            $table->foreignId('sistema_id')->constrained('sistemas');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('formato', ['xlsx', 'ods', 'csv']);
            $table->enum('estado', ['iniciado', 'generado', 'fallido'])->default('iniciado');
            $table->unsignedBigInteger('filas')->default(0);
            $table->string('ip_origen', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('token_hash', 64)->nullable();
            $table->text('mensaje_error')->nullable();
            $table->timestamp('fecha_generacion')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['sistema_id', 'created_at']);
            $table->index(['reporte_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejecuciones_reporte');
    }
};
