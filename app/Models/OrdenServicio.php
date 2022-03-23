<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenServicio extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orden_servicios';

    protected $fillable =[

        'id',
        'cliente',
        'maquina',
        'horometroInicial',
        'horasPromedio',
        'valorXhora',
        'descuento',
        'pagare',
        'valorIda',
        'valorVuelta',

    ];

    public function cliente(){
        return $this->belongsTo(Clientes::class,'cliente','id');
    }

    public function maquina(){
        return $this->belongsTo(Maquinas::class,'maquina','id');
    }

    public function accesorio(){
        return $this->belongsToMany(Accesorios::class,'rel_orden_servicio', 'orden', 'accesorio');
    }

}
