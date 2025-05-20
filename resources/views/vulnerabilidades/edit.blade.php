<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            ✏️ Editar Vulnerabilidad
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-10 sm:px-6 lg:px-8">
        <form action="{{ route('vulnerabilidades.update', $vulnerabilidad->id) }}" method="POST" class="bg-white dark:bg-gray-800 shadow p-6 rounded space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', $vulnerabilidad->nombre) }}"
                       class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Componente Afectado</label>
                <input type="text" name="componente_afectado" value="{{ old('componente_afectado', $vulnerabilidad->componente_afectado) }}"
                       class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Criticidad</label>
                <select name="criticidad"
                        class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @foreach(['Alta', 'Media', 'Baja'] as $nivel)
                        <option value="{{ $nivel }}" @selected(old('criticidad', $vulnerabilidad->criticidad) == $nivel)>
                            {{ $nivel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                <select name="estado"
                        class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    @foreach(['Detectada', 'En evaluación', 'Corregida', 'En cuarentena', 'Falso positivo'] as $estado)
                        <option value="{{ $estado }}" @selected(old('estado', $vulnerabilidad->estado) == $estado)>
                            {{ $estado }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">CVSS (0-10)</label>
                <input type="number" step="0.1" max="10" name="cvss" value="{{ old('cvss', $vulnerabilidad->cvss) }}"
                       class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Detección</label>
                <input type="date" name="fecha_deteccion" value="{{ old('fecha_deteccion', \Illuminate\Support\Str::limit($vulnerabilidad->fecha_deteccion, 10, '')) }}"
                       class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
                <textarea name="descripcion" rows="4"
                          class="mt-1 block w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('descripcion', $vulnerabilidad->descripcion) }}</textarea>
            </div>

            <div class="flex justify-end">
                <a href="{{ route('vulnerabilidades.index') }}"
                   class="mr-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-500">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
                    💾 Guardar cambios
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
