<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_ventas';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_venta',
        'subtotal'
    ];

    // Relación con la venta (Un detalle pertenece a una venta)
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    // Relación con el producto (Un detalle corresponde a un producto)
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
