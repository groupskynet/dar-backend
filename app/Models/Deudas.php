<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deudas extends Model
{
    use HasFactory;

    protected $table = 'deudas';

    protected $fillable = [
        'mantenimiento',
        'valor',
        'estado',
        'created_at',
        'updated_at'
    ];

    public function mantenimiento() {
        return $this->belongsTo(Mantenimientos::class, 'mantenimiento', 'id');
    }
}