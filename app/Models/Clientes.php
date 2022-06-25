<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clientes extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable=[
        'id',
        'tipo',
        'cedula',
        'nombres',
        'telefono',
        'direccion',
        'email',
        'nit',
        'razonSocial',
        'iva'

    ];
}
