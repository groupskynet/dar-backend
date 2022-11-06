<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accesorios extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $fillable = [
        'id',
        'maquina',
        'nombre',
        'serie',
        'marca',
        'modelo',
        'linea',
        'registro',
    ];

    public function marca()
    {
        return $this->belongsTo(Marcas::class, 'marca', 'id')->withTrashed();
    }
    public function maquina()
    {
        return $this->belongsTo(Maquinas::class, 'maquina', 'id')->withTrashed();
    }

    public function pagos()
    {
        return $this->hasMany(PagoAccesorio::class, 'accesorio_id', 'id');
    }
}
