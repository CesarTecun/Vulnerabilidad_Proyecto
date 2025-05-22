<x-app-layout>
    <x-slot name="header">
        <h2 class="px-4 py-2 text-gray-500 text-xl font-semibold text-gray-800 dark:text-gray-200">
            📋 Detalles de la Vulnerabilidad
        </h2>
    </x-slot>

    <div class="px-4 py-2 text-gray-500 max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-4 text-sm">
        <div class="px-4 py-2 text-gray-500 dark:bg-gray-800  rounded shadow">
            <h3 class="px-4 py-2 text-gray-500 text-lg font-bold mb-2">🔎 {{ $vulnerabilidad->nombre }}</h3>

            <p><strong>🧩 Componente Afectado:</strong> {{ $vulnerabilidad->componente_afectado }}</p>
            <p><strong>⚠️ Criticidad:</strong> {{ $vulnerabilidad->criticidad }}</p>
            <p><strong>📈 CVSS:</strong> {{ $vulnerabilidad->cvss }}</p>
            <p><strong>📆 Fecha de Detección:</strong> {{ $vulnerabilidad->fecha_deteccion }}</p>
            <p><strong>📍 Estado:</strong> {{ $vulnerabilidad->estado }}</p>

            @if ($vulnerabilidad->descripcion)
                <div class="mt-4">
                    <strong>📝 Descripción:</strong>
                    <div class="px-4 py-2 text-gray-500 dark:bg-gray-700 p-3 rounded mt-1 text-gray-800 dark:text-gray-200 text-sm whitespace-pre-line">
                        {!! nl2br(e($vulnerabilidad->descripcion)) !!}
                    </div>
                </div>
            @endif

            @if($vulnerabilidad->fragmento_detectado)
                <div class="mt-4">
                    <strong>📂 Fragmento Detectado:</strong>
                    <pre class="px-4 py-2 text-gray-500 text-gray-500 bg-black  p-3 rounded mt-1 text-xs overflow-x-auto leading-relaxed">
@php
    $lineas = explode("\n", $vulnerabilidad->fragmento_detectado);
    $base = $vulnerabilidad->linea_detectada ?? 1;
@endphp
@foreach($lineas as $i => $linea)
{{ str_pad($base + $i, 4, ' ', STR_PAD_LEFT) }} | {{ $linea }}
@endforeach
                    </pre>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('vulnerabilidades.index') }}"
                   class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                    ← Volver al listado
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
@auth
    {{-- Notificación flotante --}}
    @php
        Auth::user()->refresh();
        $pendientes = Auth::user()->unreadNotifications()->take(3);
    @endphp

    @if ($pendientes->count())
        {{-- Aquí va el bloque corregido que te mostré arriba --}}
    @endif
@endauth
