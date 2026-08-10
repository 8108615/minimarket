<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio_venta',
        'subtotal'
    ];

    // Relación: Un detalle pertenece a una venta
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    // Relación: Un detalle pertenece a un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
