<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoMaquina extends Model
{
    use HasFactory;
    protected $table = 'pagos_maquinas';

    protected $fillable = ['maquina_id', 'valor', 'fecha_inicio', 'fecha_fin'];

    public function maquina()
    {
        return $this->belongsTo(Maquinas::class, 'maquina_id', 'id')
            ->withTrashed();
    }
}
