<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Patrones de Vulnerabilidad
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded dark:bg-green-900 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('patrones.create') }}"
               class="px-4 py-2 text-gray-500 bg-blue-600 rounded hover:bg-blue-700 text-sm">
                ➕ Nuevo Patrón
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded">
            <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Regex</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Criticidad</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-x divide-gray-200 dark:divide-gray-700">
                    @forelse ($patrones as $patron)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $patron->nombre }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $patron->regex }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex px-6 py-4 text-sm text-gray-600 dark:text-gray-300 text-xs font-medium rounded-full
                                    @class([
                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' => $patron->criticidad === 'Alta',
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' => $patron->criticidad === 'Media',
                                        'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' => $patron->criticidad === 'Baja',
                                    ])">
                                    {{ $patron->criticidad }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm space-x-2">
                                <a href="{{ route('patrones.edit', $patron) }}"
                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Editar</a>
                                <form action="{{ route('patrones.destroy', $patron) }}" method="POST" class="inline"
                                      onsubmit="return confirm('¿Eliminar este patrón?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No hay patrones registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
