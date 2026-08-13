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
        'caja_id', // <--- AGREGADO
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

    // ... (cliente, user y detalles se mantienen igual)

    // Relación con la caja (Una venta pertenece a una caja)
    public function caja()
    {
        return $this->belongsTo(Caja::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
