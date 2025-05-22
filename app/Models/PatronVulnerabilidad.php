<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatronVulnerabilidad extends Model
{
    // Nombre correcto de la tabla en la base de datos
    protected $table = 'patron_vulnerabilidades';

    protected $fillable = ['nombre', 'regex', 'criticidad'];
}
