<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'nombres',
        'ci',
        'telefono',
        'email',
        'direccion',
        'estado'
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class); // Ajusta 'Venta::class' según el nombre real de tu modelo de ventas
    }


}
