<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Mi título</h2>
    </x-slot>

    <div class="py-6">
        <!-- contenido -->
    </div>


<div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100">🛡 Vulnerabilidades Detectadas</h1>

    <a href="{{ route('vulnerabilidades.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded inline-block mb-4 transition dark:bg-blue-500 dark:hover:bg-blue-600">
        ➕ Nueva Vulnerabilidad
    </a>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 mb-4 dark:bg-green-700 dark:text-white dark:border-green-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow">
        <table class="w-full table-auto divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Nombre</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Componente</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Criticidad</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">CVSS</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Estado</th>
                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Fecha</th>
                    <th class="px-4 py-2 text-right text-sm font-semibold text-gray-700 dark:text-gray-300">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($vulnerabilidades as $v)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $v->nombre }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $v->componente_afectado }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $v->criticidad }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $v->cvss }}</td>
                    <td class="px-4 py-2 text-sm">
                        <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                            @class([
                                'bg-red-100 text-red-800 dark:bg-red-700 dark:text-white' => $v->estado === 'Detectada',
                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-white' => $v->estado === 'En evaluación',
                                'bg-green-100 text-green-800 dark:bg-green-600 dark:text-white' => $v->estado === 'Corregida',
                                'bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white' => $v->estado === 'En cuarentena',
                                'bg-purple-100 text-purple-800 dark:bg-purple-600 dark:text-white' => $v->estado === 'Falso positivo',
                            ])
                        ">
                            {{ $v->estado }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">
                        {{ $v->fecha_deteccion ? \Carbon\Carbon::parse($v->fecha_deteccion)->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-2 text-sm text-right space-x-2">
                        <a href="{{ route('vulnerabilidades.edit', $v->id) }}"
                           class="text-blue-600 dark:text-blue-400 text-xs font-semibold hover:underline">✏️ Editar</a>
                        <form action="{{ route('vulnerabilidades.destroy', $v->id) }}" method="POST" class="inline-block"
                              onsubmit="return confirm('¿Deseas eliminar esta vulnerabilidad?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-red-600 dark:text-red-400 text-xs font-semibold hover:underline">
                                🗑 Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        No se han registrado vulnerabilidades.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>
