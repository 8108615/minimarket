<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;

    protected $table = 'cajas';

    protected $fillable = [
        'user_id',
        'saldo_inicial',
        'saldo_final',
        'total_ventas',
        'fecha_apertura',
        'fecha_cierre',
        'estado',
    ];

    // Relación con el usuario que abrió la caja
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con las ventas realizadas en esta caja
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class);
    }
}
