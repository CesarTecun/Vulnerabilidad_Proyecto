<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;

class AnalizadorVulnerabilidad
{
    protected array $patrones = [
        ['nombre' => 'Uso de Log4j', 'regex' => '/org\.apache\.log4j/', 'criticidad' => 'Alta'],
        ['nombre' => 'Uso de eval() inseguro', 'regex' => '/eval\s*\(/', 'criticidad' => 'Alta'],
        ['nombre' => 'Uso de Struts2 vulnerable', 'regex' => '/Struts2\-core\-2\.3\.\d+/', 'criticidad' => 'Alta'],
        ['nombre' => 'IP en lista negra', 'regex' => '/45\.89\.23\.100/', 'criticidad' => 'Media'],
        [
            'nombre' => 'Posible SQL Injection',
            'regex' => '/\b(SELECT|UPDATE|DELETE|INSERT)\b.*\bFROM\b.*\$_(GET|POST|REQUEST|COOKIE)/i',
            'criticidad' => 'Alta',
        ],
    ];

    public function escanear($ruta)
    {
        $contenido = Storage::get($ruta);
        $lineas = explode("\n", $contenido);
        $detectadas = [];
    
        foreach ($this->patrones as $p) {
            foreach ($lineas as $i => $linea) {
                if (preg_match($p['regex'], $linea, $match)) {
                    $detectadas[] = [
                        'nombre' => $p['nombre'],
                        'detalle' => $match[0],
                        'criticidad' => $p['criticidad'],
                        'archivo' => $ruta,
                        'linea' => $i + 1, // línea exacta (1-indexed)
                        'fragmento' => trim($linea), // línea del código detectada
                    ];
                }
            }
        }
    
        return $detectadas;
    }
    
}
