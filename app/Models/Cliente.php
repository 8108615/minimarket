<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        'nombres',
        'ci',
        'telefono',
        'email',
        'direccion',
        'estado'
    ];
}
