<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vulnerabilidad extends Model
{
    use HasFactory;

    protected $table = 'vulnerabilidades';

    protected $fillable = [
        'nombre',
        'componente_afectado',
        'criticidad',
        'estado',
        'fecha_deteccion',
        'cvss',
        'descripcion',
    ];

    protected $casts = [
        'fecha_deteccion' => 'datetime', // ✅ ahora sí correctamente casteado
    ];
}
