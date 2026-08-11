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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('tipo_comprobante', ['Boleta', 'Factura'])->default('Boleta');
            $table->string('numero_comprobante')->unique();
            $table->enum('metodo_pago', ['Efectivo', 'QR', 'Tarjeta'])->default('Efectivo');
            $table->string('codigo_transaccion')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamp('fecha_venta')->useCurrent();
            $table->decimal('monto_recibido', 10, 2);
            $table->decimal('vuelto_entregado', 10, 2);
            $table->string('estado')->default('Completado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
