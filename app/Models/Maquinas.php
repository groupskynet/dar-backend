<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maquinas extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'id',
        'nombre',
        'serie',
        'marca',
        'modelo',
        'horometro',
        'linea',
        'estado',
        'operador',
        'registro',
        'placa',
        'tipo'
    ];

    public function setChoferAttribute($value)
    {
        $this->attributes['operador'] =  $value;
    }

    public function marca()
    {
        return $this->belongsTo(Marcas::class, 'marca', 'id')
            ->withTrashed();
    }

    public function accesorios()
    {
        return $this->hasMany(Accesorios::class, 'maquina', 'id');
    }

    public function operador()
    {
        return $this->belongsTo(Operadores::class, 'operador', 'id')->withTrashed();
    }
}
