<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Mi título</h2>
    </x-slot>

    <div class="py-6">
        <!-- contenido -->
    </div>


<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">🛠 Registrar Nueva Vulnerabilidad</h1>

    <form action="{{ route('vulnerabilidades.store') }}" method="POST" class="space-y-4 bg-white dark:bg-gray-800 p-6 rounded shadow">
        @csrf

        {{-- Nombre --}}
        <div>
            <label class="block font-semibold text-gray-700 dark:text-gray-300">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}"
                   class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-2" required>
            @error('nombre') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Componente --}}
        <div>
            <label class="block font-semibold text-gray-700 dark:text-gray-300">Componente Afectado</label>
            <input type="text" name="componente_afectado" value="{{ old('componente_afectado') }}"
                   class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-2" required>
            @error('componente_afectado') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Criticidad --}}
        <div>
            <label class="block font-semibold text-gray-700 dark:text-gray-300">Criticidad</label>
            <select name="criticidad"
                    class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-2" required>
                @foreach(['Alta', 'Media', 'Baja'] as $nivel)
                    <option value="{{ $nivel }}" @selected(old('criticidad') == $nivel)>{{ $nivel }}</option>
                @endforeach
            </select>
            @error('criticidad') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Estado --}}
        <div>
            <label class="block font-semibold text-gray-700 dark:text-gray-300">Estado</label>
            <select name="estado"
                    class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-2" required>
                @foreach(['Detectada', 'En evaluación', 'Corregida', 'En cuarentena', 'Falso positivo'] as $estado)
                    <option value="{{ $estado }}" @selected(old('estado') == $estado)>{{ $estado }}</option>
                @endforeach
            </select>
            @error('estado') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Fecha --}}
        <div>
            <label class="block font-semibold text-gray-700 dark:text-gray-300">Fecha de Detección</label>
            <input type="date" name="fecha_deteccion" value="{{ old('fecha_deteccion') }}"
                   class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-2">
            @error('fecha_deteccion') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- CVSS --}}
        <div>
            <label class="block font-semibold text-gray-700 dark:text-gray-300">Puntaje CVSS</label>
            <input type="number" name="cvss" step="0.1" min="0" max="10" value="{{ old('cvss') }}"
                   class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-2">
            @error('cvss') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Descripción --}}
        <div>
            <label class="block font-semibold text-gray-700 dark:text-gray-300">Descripción</label>
            <textarea name="descripcion" rows="4"
                      class="w-full rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white p-2">{{ old('descripcion') }}</textarea>
            @error('descripcion') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        {{-- Botones --}}
        <div class="text-right">
            <a href="{{ route('vulnerabilidades.index') }}"
               class="text-gray-600 dark:text-gray-300 hover:underline mr-4">Cancelar</a>
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded dark:bg-green-500 dark:hover:bg-green-600">
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