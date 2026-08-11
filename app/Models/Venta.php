<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'cliente_id',
        'user_id',
        'tipo_comprobante',
        'numero_comprobante',
        'metodo_pago',
        'codigo_transaccion',
        'subtotal',
        'total',
        'fecha_venta',
        'monto_recibido',
        'vuelto_entregado',
        'estado'
    ];

    // Relación con el cliente (Una venta pertenece a un cliente)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación con el usuario/cajero (Una venta fue realizada por un usuario)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con los detalles (Una venta tiene muchos detalles de productos)
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
