<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gastos extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =[
        'id',
        'maquina',
        'valor',
        'descripcion',
        'soporte',
    ];

    public function maquina(){
        return $this->belongsTo(Maquinas::class, 'maquina', 'id');

    }
}
