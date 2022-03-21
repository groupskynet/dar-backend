<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\FuncCall;

class Tickets extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =[

        'cliente',
        'fecha',
        'nOrden',
        'maquina',
        'accesorio',
        'horometroInicial',
        'horometroFinal',
        'galones',
        'costo'
    ];

    public function cliente(){
        return $this->belongsTo(Clientes::class,'cliente','id');
    }

    public Function maquina(){
        return $this->belongsTo(Maquinas::class, 'maquina','id');
    }

    public function accesorio (){
        return $this->belongsTo(Accesorios::class,'accesorio','id');
    }
}
