<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'precio_venta',
        'precio_compra',
        'stock',
        'stock_minimo',
        'categoria_id',
        'estado',
        'imagen'
    ];

    // Relación con Categoría
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relación con Detalle de Compras
    public function detallesCompra(): HasMany
    {
        return $this->hasMany(DetalleCompra::class);
    }
}
