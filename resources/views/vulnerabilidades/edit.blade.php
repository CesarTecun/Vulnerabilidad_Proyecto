<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            ✏️ Editar Vulnerabilidad
        </h2>
    </x-slot>

<div class="max-w-md mx-auto py-4 sm:px-3">
    <form action="{{ route('vulnerabilidades.update', $vulnerabilidad->id) }}" method="POST"
          class="bg-white dark:bg-gray-800 shadow px-4 py-4 rounded-lg space-y-4 text-sm">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre', $vulnerabilidad->nombre) }}"
                   class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white h-9 text-sm">
        </div>

        <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300">Componente Afectado</label>
            <input type="text" name="componente_afectado" value="{{ old('componente_afectado', $vulnerabilidad->componente_afectado) }}"
                   class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white h-9 text-sm">
        </div>

        <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300">Criticidad</label>
            <select name="criticidad"
                    class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white h-9 text-sm">
                @foreach(['Alta', 'Media', 'Baja'] as $nivel)
                    <option value="{{ $nivel }}" @selected(old('criticidad', $vulnerabilidad->criticidad) == $nivel)>
                        {{ $nivel }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300">Estado</label>
            <select name="estado"
                    class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white h-9 text-sm">
                @foreach(['Detectada', 'En evaluación', 'Corregida', 'En cuarentena', 'Falso positivo'] as $estado)
                    <option value="{{ $estado }}" @selected(old('estado', $vulnerabilidad->estado) == $estado)>
                        {{ $estado }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300">CVSS (0-10)</label>
            <input type="number" step="0.1" max="10" name="cvss" value="{{ old('cvss', $vulnerabilidad->cvss) }}"
                   class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white h-9 text-sm">
        </div>

        <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300">Fecha de Detección</label>
            <input type="date" name="fecha_deteccion"
                   value="{{ old('fecha_deteccion', \Illuminate\Support\Str::limit($vulnerabilidad->fecha_deteccion, 10, '')) }}"
                   class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white h-9 text-sm">
        </div>

        <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300">Descripción</label>
            <textarea name="descripcion" rows="3"
                      class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">{{ old('descripcion', $vulnerabilidad->descripcion) }}</textarea>
        </div>

        @if(isset($contexto) && count($contexto))
        <div>
            <label class="block font-medium text-gray-700 dark:text-gray-300 mb-2">📂 Código Detectado</label>
            <pre class="bg-gray-900 text-white text-xs rounded p-2 overflow-x-auto max-h-40">
@foreach($contexto as $linea)
{!! $linea['resaltado']
    ? "<span style='background-color: #ef4444; color: #fff;'>".str_pad($linea['num'], 4, ' ', STR_PAD_LEFT)." | ".e($linea['contenido'])."</span>"
    : "<span>".str_pad($linea['num'], 4, ' ', STR_PAD_LEFT)." | ".e($linea['contenido'])."</span>" !!}
@endforeach
            </pre>
        </div>
        @endif

        <div class="flex justify-end space-x-2 pt-2">
            <a href="{{ route('vulnerabilidades.index') }}"
               class="px-3 py-1.5 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm transition">
                Cancelar
            </a>
            <button type="submit"
                    class="px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700 text-sm transition">
                Guardar
            </button>
        </div>
    </form>
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
