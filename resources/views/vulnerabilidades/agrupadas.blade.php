<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">
            📂 Vulnerabilidades agrupadas por 
            <span class="capitalize">{{ str_replace('_', ' ', request('criterio', 'componente_afectado')) }}</span>
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 space-y-6">

        {{-- Selector de agrupación --}}
        <form method="GET" class="mb-6 flex gap-3 items-center">
            <label for="criterio" class="text-sm font-medium text-gray-700 dark:text-gray-300">Agrupar por:</label>
            <select name="criterio" id="criterio" onchange="this.form.submit()"
                    class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1">
                <option value="componente_afectado" @selected(request('criterio') == 'componente_afectado')>Componente</option>
                <option value="tipo" @selected(request('criterio') == 'tipo')>Tipo de Vulnerabilidad</option>
                <option value="criticidad" @selected(request('criterio') == 'criticidad')>Criticidad</option>
                <option value="estado" @selected(request('criterio') == 'estado')>Estado</option>
            </select>
        </form>

        {{-- Grupos de vulnerabilidades --}}
        @forelse ($agrupadas as $grupo => $vulnerabilidades)
            <div class="bg-white dark:bg-gray-800 rounded shadow p-4 border dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">📄 {{ $grupo }}</h3>
                <ul class="list-disc list-inside text-sm text-gray-700 dark:text-gray-300 space-y-1">
                    @foreach ($vulnerabilidades as $v)
                        <li>
                            <span class="font-bold">{{ $v->nombre }}</span> — {{ $v->descripcion }}

                            @if(request('criterio') !== 'componente_afectado')
                                <span class="text-xs text-gray-500 ml-1">[Archivo: {{ $v->componente_afectado }}]</span>
                            @endif

                            @if($v->linea_detectada)
                                <span class="text-xs text-gray-500 ml-1">[Línea {{ $v->linea_detectada }}]</span>
                            @endif

                            <a href="{{ route('vulnerabilidades.edit', $v->id) }}"
                               class="text-blue-600 dark:text-blue-300 text-xs ml-2 hover:underline">
                                ✏️ Editar
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @empty
            <div class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 p-4 rounded shadow">
                No se encontraron vulnerabilidades agrupadas.
            </div>
        @endforelse
    </div>
</x-app-layout>
