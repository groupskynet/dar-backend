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

    protected $fillable = [
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
        'estado'
    ];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'cliente', 'id')->withTrashed();
    }

    public function maquina()
    {
        return $this->belongsTo(Maquinas::class, 'maquina', 'id')->withTrashed();
    }

    public function accesorios()
    {
        return $this->belongsToMany(Accesorios::class, 'rel_orden_servicio', 'orden', 'accesorio')
            ->withPivot('valorXhora')->withTrashed();
    }

    public function tickets()
    {
        return $this->hasMany(Tickets::class, 'orden', 'id');
    }

}