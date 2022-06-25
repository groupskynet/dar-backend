<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAbonos extends Model
{
    use HasFactory;
    protected $table = 'detalle-abono';
    protected $fillable = ['deuda', 'valor', 'created_at', 'updated_at'];

    public function deuda()
    {
        return $this->belongsTo(Deudas::class, 'deuda', 'id');
    }
}
