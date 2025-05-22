<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PatronVulnerabilidad;

class PatronVulnerabilidadSeeder extends Seeder
{
    public function run(): void
    {
        $patrones = [
            ['nombre' => 'Uso de Log4j', 'regex' => 'org\.apache\.log4j', 'criticidad' => 'Alta'],
            ['nombre' => 'Uso inseguro de eval()', 'regex' => 'eval\s*\(', 'criticidad' => 'Alta'],
            ['nombre' => 'Uso de Struts2 2.3.x', 'regex' => 'Struts2\-core\-2\.3\.\d+', 'criticidad' => 'Alta'],
            ['nombre' => 'IP en lista negra', 'regex' => '45\.89\.23\.100', 'criticidad' => 'Media'],
            ['nombre' => 'Posible SQL Injection', 'regex' => '\b(SELECT|UPDATE|DELETE|INSERT)\b.*\bFROM\b.*\$_(GET|POST|REQUEST|COOKIE)', 'criticidad' => 'Alta'],
        ];

        foreach ($patrones as $patron) {
            PatronVulnerabilidad::create($patron);
        }
    }
}
