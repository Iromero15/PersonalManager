<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            
            // Clave foránea: Esto vincula el gasto con el usuario que lo creó
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // El tipo de gasto (Como los Enums que mencionabas de PostgreSQL)
            $table->enum('type', ['expense', 'income', 'loan_sent', 'loan_received']);
            
            // El monto: 10 dígitos en total, 2 decimales (ej: 99999999.99)
            // Siempre sin signo (unsigned) porque el 'type' define si es ingreso o egreso
            $table->decimal('amount', 10, 2)->unsigned();
            
            // Categoría (podría ser otra tabla a futuro, pero para el MVP un string va perfecto)
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            
            // Comentario chico, le permitimos ser nulo por si te olvidás de anotarlo
            $table->string('description', 255)->nullable();
            
            // Fecha exacta del movimiento (distinta a la fecha de creación del registro en la DB)
            $table->date('transaction_date');

            // Esto te crea automáticamente el created_at y updated_at
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};