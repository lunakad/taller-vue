<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sistemas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->enum('motor', ['mysql', 'pgsql']);
            $table->string('host', 150);
            $table->unsignedInteger('puerto');
            $table->string('base_datos', 150);
            $table->string('usuario_bd', 150);
            $table->text('clave_bd'); // cifrada con Crypt::encryptString
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sistemas');
    }
};
