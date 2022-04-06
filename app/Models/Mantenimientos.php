<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mantenimientos extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable =[
        'id',
        'tipo',
        'maquina',
        'proveedor',
        'descricion',
        'horometro',
        'modalidad',
        'costo',
        'abono',
        'soporte'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedores::class,'proveedor','id')->withTrashed();
    }

    public function maquina()
    {
        return $this->belongsTo(Maquinas::class, 'maquina', 'id')->withTrashed();
    }   
    
}
