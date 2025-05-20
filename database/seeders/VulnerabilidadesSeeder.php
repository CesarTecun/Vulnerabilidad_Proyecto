<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vulnerabilidad;

class VulnerabilidadesSeeder extends Seeder
{
    public function run(): void
    {
        $datos = [
            [
                'nombre' => 'Log4Shell',
                'componente_afectado' => 'log4j-core',
                'criticidad' => 'Alta',
                'estado' => 'Detectada',
                'fecha_deteccion' => '2021-12-09',
                'cvss' => 10.0,
                'descripcion' => 'Vulnerabilidad crítica de ejecución remota en Apache Log4j 2. Permitía ejecución de código con solo registrar una cadena especialmente diseñada.'
            ],
            [
                'nombre' => 'Heartbleed',
                'componente_afectado' => 'OpenSSL',
                'criticidad' => 'Alta',
                'estado' => 'Corregida',
                'fecha_deteccion' => '2014-04-07',
                'cvss' => 9.4,
                'descripcion' => 'Permite leer la memoria del servidor afectado a través del protocolo TLS/SSL. Filtra claves privadas y datos confidenciales.'
            ],
            [
                'nombre' => 'Shellshock',
                'componente_afectado' => 'GNU Bash',
                'criticidad' => 'Alta',
                'estado' => 'Corregida',
                'fecha_deteccion' => '2014-09-24',
                'cvss' => 9.8,
                'descripcion' => 'Permite ejecución remota de comandos a través de variables de entorno manipuladas.'
            ],
            [
                'nombre' => 'Dirty COW',
                'componente_afectado' => 'Linux Kernel',
                'criticidad' => 'Media',
                'estado' => 'En evaluación',
                'fecha_deteccion' => '2016-10-19',
                'cvss' => 7.8,
                'descripcion' => 'Vulnerabilidad en manejo de memoria que permite escalación de privilegios en sistemas Linux.'
            ],
            [
                'nombre' => 'Spectre',
                'componente_afectado' => 'CPU Intel/AMD',
                'criticidad' => 'Media',
                'estado' => 'En cuarentena',
                'fecha_deteccion' => '2018-01-03',
                'cvss' => 5.6,
                'descripcion' => 'Aprovecha ejecución especulativa de CPU para filtrar información entre procesos.'
            ],
        ];

        foreach ($datos as $v) {
            Vulnerabilidad::create($v);
        }
    }
}
