<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rezagos', function (Blueprint $table) {
            $table->id();

            // Campos solicitados
            $table->string('codigo')->unique();          // CODIGO
            $table->string('destinatario')->nullable();  // DESTINATARIO
            $table->string('telefono', 30)->nullable();  // TELEFONO
            $table->decimal('peso', 10, 3)->nullable();  // PESO (kg)
            $table->string('aduana')->nullable();        // ADUANA
            $table->string('zona')->nullable();          // ZONA
            $table->string('tipo')->nullable();          // TIPO

            // Extras pedidos
            $table->string('estado')->default('PRE REZAGO'); // ESTADO
            $table->string('ciudad')->nullable();           // CIUDAD

            // Opcionales útiles
            $table->string('observacion')->nullable();
            $table->timestamps();
            $table->softDeletes();

            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rezagos');
    }
};
