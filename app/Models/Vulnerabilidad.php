<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vulnerabilidad extends Model
{
    use HasFactory;

    protected $table = 'vulnerabilidades'; // ← nombre correcto de la tabla

    protected $fillable = [
        'nombre',
        'componente_afectado',
        'criticidad',
        'descripcion',
        'estado',
    ];
}
