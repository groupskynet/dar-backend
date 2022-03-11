<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maquinas extends Model
{
    use HasFactory;

    public $fillable = [
        'id',
        'nombre',
        'serie',
        'marca',
        'modelo',
        'linea',
        'registro',
        'placa',
        'tipo'
    ];

    public function marca(){
        return $this->belongsTo(Marcas::class, 'marca', 'id');
    }

}
