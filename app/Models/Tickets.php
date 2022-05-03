<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tickets extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'orden',
        'cliente',
        'fecha',
        'maquina',
        'accesorio',
        'horometroInicial',
        'horometroFinal',
        'galones',
        'costo',
        'soporte',
        'operador',
        'estado'
    ];

    public function getNumeroOrdenAttribute(): string
    {
        return str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'cliente', 'id');
    }

    public function operador()
    {
        return $this->belongsTo(Operadores::class, 'operador', 'id');
    }

    public function maquina()
    {
        return $this->belongsTo(Maquinas::class, 'maquina', 'id');
    }

    public function accesorio()
    {
        return $this->belongsTo(Accesorios::class, 'accesorio', 'id');
    }
}
