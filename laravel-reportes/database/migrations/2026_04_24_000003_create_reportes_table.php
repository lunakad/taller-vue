<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sistema_id')->constrained('sistemas');
            $table->string('codigo', 60);
            $table->string('nombre', 180);
            $table->text('descripcion')->nullable();
            $table->longText('consulta_sql');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['sistema_id', 'codigo']);
            $table->index(['sistema_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
