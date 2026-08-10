<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'user_id',
        'total',
        'metodo_pago',
        'estado',
        'fecha_venta'
    ];

    // Constantes para métodos de pago
    const METODO_EFECTIVO = 'Efectivo';
    const METODO_QR = 'QR';
    const METODO_TARJETA = 'Tarjeta';

    public static function metodosPago()
    {
        return [
            self::METODO_EFECTIVO => 'Efectivo',
            self::METODO_QR => 'QR',
            self::METODO_TARJETA => 'Tarjeta',
        ];
    }

    // Relación: Una venta pertenece a un cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relación: Una venta pertenece a un usuario (quien la realizó)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Una venta tiene muchos detalles
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
