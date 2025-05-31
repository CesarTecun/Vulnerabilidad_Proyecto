<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Detalles de la Vulnerabilidad
            </h2>
            <a href="{{ route('vulnerabilidades.index') }}"
               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <!-- Encabezado de la tarjeta -->
            <div class="bg-indigo-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-xl font-bold text-indigo-700 dark:text-indigo-300 flex items-center">
                    <span class="mr-2"></span> {{ $vulnerabilidad->nombre }}
                </h3>
            </div>
            
            <!-- Contenido principal -->
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="font-semibold text-gray-800 dark:text-gray-100">Componente Afectado:</span> 
                            <span class="block mt-1 px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded">{{ $vulnerabilidad->componente_afectado }}</span>
                        </p>
                        
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="font-semibold text-gray-800 dark:text-gray-100">Criticidad:</span> 
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium 
                                @if($vulnerabilidad->criticidad === 'Alta') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @elseif($vulnerabilidad->criticidad === 'Media') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                {{ $vulnerabilidad->criticidad }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="space-y-2">
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="font-semibold text-gray-800 dark:text-gray-100">CVSS:</span> 
                            <span class="block mt-1 px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded">{{ $vulnerabilidad->cvss }}</span>
                        </p>
                        
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="font-semibold text-gray-800 dark:text-gray-100">Fecha de Detección:</span> 
                            <span class="block mt-1 px-3 py-2 bg-gray-50 dark:bg-gray-700 rounded">{{ $vulnerabilidad->fecha_deteccion }}</span>
                        </p>
                        
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="font-semibold text-gray-800 dark:text-gray-100">Estado:</span> 
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium 
                                @if($vulnerabilidad->estado === 'Pendiente') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($vulnerabilidad->estado === 'Resuelta') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                {{ $vulnerabilidad->estado }}
                            </span>
                        </p>
                    </div>
                </div>

                @if ($vulnerabilidad->descripcion)
                    <div class="mt-8">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center">
                            <span class="mr-2">📝</span> Descripción
                        </h4>
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-800 dark:text-gray-200 whitespace-pre-line">
                            <pre class="whitespace-pre-wrap text-sm leading-relaxed font-mono">{{ $vulnerabilidad->descripcion }}</pre>
                        </div>
                    </div>
                @endif

                @if($vulnerabilidad->fragmento_detectado)
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2 flex items-center">
                            <span class="mr-2">📂</span> Fragmento Detectado
                        </h4>
                        <div class="relative">
                            <pre class="bg-gray-900 text-gray-300 p-4 rounded-lg overflow-x-auto text-sm leading-relaxed font-mono">
@php
    $lineas = explode("\n", $vulnerabilidad->fragmento_detectado);
    $base = $vulnerabilidad->linea_detectada ?? 1;
@endphp
@foreach($lineas as $i => $linea)
<span class="text-gray-500">{{ str_pad($base + $i, 4, ' ', STR_PAD_LEFT) }} |</span> {{ $linea }}
@endforeach
                            </pre>
                            <button onclick="copyCode()" class="absolute top-2 right-2 p-1 bg-gray-700 rounded text-gray-300 hover:bg-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" />
                                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Pie de tarjeta -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600 flex justify-end">
            <a href="{{ route('vulnerabilidades.pdf', $vulnerabilidad->id) }}"
                target="_blank"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                📄 Exportar a PDF
                </a>    
            <a href="{{ route('vulnerabilidades.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2 text-gray-800 dark:text-gray-200 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 font-medium rounded-md transition-all duration-200 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Volver al listado
                </a>
                
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function copyCode() {
    const code = document.querySelector('pre').innerText;
    navigator.clipboard.writeText(code).then(() => {
        // Opcional: Mostrar notificación de copiado
        alert('Código copiado al portapapeles');
    });
}
</script>
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
