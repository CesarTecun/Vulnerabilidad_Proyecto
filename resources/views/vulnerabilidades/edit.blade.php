<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Gestionar Vulnerabilidad
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

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form action="{{ route('vulnerabilidades.update', $vulnerabilidad->id) }}" method="POST"
              class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            @csrf
            @method('PUT')

            <div class="bg-indigo-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-indigo-700 dark:text-indigo-300">
                    Editar información de vulnerabilidad
                </h3>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Columna izquierda -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nombre de la vulnerabilidad
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" value="{{ old('nombre', $vulnerabilidad->nombre) }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 text-sm"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Componente afectado
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="componente_afectado" value="{{ old('componente_afectado', $vulnerabilidad->componente_afectado) }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 text-sm"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nivel de criticidad
                            </label>
                            <select name="criticidad"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 text-sm">
                                @foreach(['Alta', 'Media', 'Baja'] as $nivel)
                                    <option value="{{ $nivel }}" @selected(old('criticidad', $vulnerabilidad->criticidad) == $nivel)>
                                        {{ $nivel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Estado actual
                            </label>
                            <select name="estado"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 text-sm">
                                @foreach(['Detectada', 'En evaluación', 'Corregida', 'En cuarentena', 'Falso positivo'] as $estado)
                                    <option value="{{ $estado }}" @selected(old('estado', $vulnerabilidad->estado) == $estado)>
                                        {{ $estado }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Puntuación CVSS (0-10)
                            </label>
                            <input type="number" step="0.1" min="0" max="10" name="cvss" 
                                   value="{{ old('cvss', $vulnerabilidad->cvss) }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Fecha de detección
                            </label>
                            <input type="date" name="fecha_deteccion"
                                   value="{{ old('fecha_deteccion', \Illuminate\Support\Str::limit($vulnerabilidad->fecha_deteccion, 10, '')) }}"
                                   class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 text-sm">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Descripción detallada
                    </label>
                    <textarea name="descripcion" rows="8"
                              class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('descripcion', $vulnerabilidad->descripcion) }}</textarea>
                </div>

                @if(!empty($vulnerabilidad->fragmento_detectado))
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span class="mr-1">📂</span> Fragmento de código detectado
                    </label>
                    <div class="relative">
                        <pre class="bg-gray-900 text-gray-300 p-4 rounded-lg overflow-x-auto text-sm leading-relaxed font-mono max-h-80">
@php
    $lineas = explode("\n", $vulnerabilidad->fragmento_detectado);
    $base = $vulnerabilidad->linea_detectada ?? 1;
@endphp
@foreach($lineas as $i => $linea)
<span class="text-gray-500">{{ str_pad($base + $i, 4, ' ', STR_PAD_LEFT) }} |</span> <span class="{{ $i === 0 ? 'bg-red-900 text-red-200 px-1' : '' }}">{{ $linea }}</span>
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

            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600 flex justify-end space-x-3">
                <a href="{{ route('vulnerabilidades.index') }}"
                   class="px-4 py-2 text-gray-600 dark:text-gray-200 bg-indigo-600 hover:bg-indigo-700 rounded-lg transition flex items-center">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 text-gray-600 dark:text-gray-200 border border-transparent rounded-md shadow-sm text-sm font-medium bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    Guardar cambios
                </button>
            </div>
        </form>
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
