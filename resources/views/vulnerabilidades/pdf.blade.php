<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Vulnerabilidad #{{ $vulnerabilidad->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #1a1a1a;
        }
        h1 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        h2 {
            font-size: 16px;
            margin-top: 20px;
            margin-bottom: 6px;
            border-bottom: 1px solid #ccc;
        }
        .label {
            font-weight: bold;
        }
        .box {
            background-color: #f3f3f3;
            padding: 6px 10px;
            border-radius: 4px;
            margin-top: 2px;
        }
        .tag {
            display: inline-block;
            padding: 4px 8px;
            font-size: 11px;
            border-radius: 3px;
            font-weight: bold;
            color: white;
        }
        .alta {
            background-color: #dc2626;
        }
        .media {
            background-color: #f59e0b;
        }
        .baja {
            background-color: #16a34a;
        }
        pre {
            background: #27272a;
            color: #f1f5f9;
            padding: 10px;
            border-radius: 6px;
            overflow-x: auto;
            font-family: monospace;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <h1>Detalles de la Vulnerabilidad</h1>

    <p><span class="label">Nombre:</span> {{ $vulnerabilidad->nombre }}</p>

    <p><span class="label">Componente Afectado:</span>
        <div class="box">{{ $vulnerabilidad->componente_afectado }}</div>
    </p>

    <p><span class="label">Criticidad:</span>
        @php
            $claseCrit = strtolower($vulnerabilidad->criticidad);
        @endphp
        <span class="tag {{ $claseCrit }}">{{ $vulnerabilidad->criticidad }}</span>
    </p>

    <p><span class="label">CVSS:</span>
        <div class="box">{{ $vulnerabilidad->cvss }}</div>
    </p>

    <p><span class="label">Fecha de Detección:</span>
        <div class="box">{{ \Carbon\Carbon::parse($vulnerabilidad->fecha_deteccion)->format('d-m-Y H:i:s') }}</div>
    </p>


    <p><span class="label">Estado:</span>
        <div class="box">{{ $vulnerabilidad->estado }}</div>
    </p>

    @if ($vulnerabilidad->descripcion)
        <h2>📝 Descripción</h2>
        <div class="box">{!! nl2br(e($vulnerabilidad->descripcion)) !!}</div>
    @endif

    @if ($vulnerabilidad->fragmento_detectado)
        <h2>📂 Fragmento Detectado</h2>
        <pre>
@php
    $lineas = explode("\n", $vulnerabilidad->fragmento_detectado);
    $base = $vulnerabilidad->linea_detectada ?? 1;
@endphp
@foreach($lineas as $i => $linea)
{{ str_pad($base + $i, 4, ' ', STR_PAD_LEFT) }} | {{ $linea }}
@endforeach
        </pre>
    @endif
</body>
</html>
