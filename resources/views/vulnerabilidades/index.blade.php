<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Vulnerabilidades Detectadas</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
        {{-- Barra superior de acciones --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            {{-- Botón de creación --}}
        <a href="{{ route('patrones.create') }}"
        class="inline-flex items-center px-6 py-4 text-white dark:text-gray-100 font-medium rounded-md bg-green-600 hover:bg-green-700 transition-all duration-200 ease-in-out">
            ➕ Nuevo Patrón de Vulnerabilidad
        </a>

            {{-- Formulario de escaneo --}}
            <form method="POST" action="{{ route('vulnerabilidades.detectar') }}" enctype="multipart/form-data" 
                  class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                @csrf
                <div class="relative flex-grow">
                    <input type="file" name="archivo" accept=".php,.js,.html,.txt,.zip" required
                        class="w-full text-sm dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md px-4 py-2 text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Puedes subir archivos individuales (.php, .js...) o carpetas comprimidas en <strong>.zip</strong>.
                    </p>
                </div>
                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-4 text-gray-700 dark:text-gray-300 hover:bg-indigo-700  font-medium rounded-md transition-all duration-200 ease-in-out">
                    🧪 Escanear archivo
                </button>
            </form>

            {{-- Filtros --}}
            <form method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <div class="flex gap-2">
                    <select name="criticidad" 
                            class="flex-grow min-w-[150px] rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Criticidad --</option>
                        @foreach($criticidades as $c)
                            <option value="{{ $c }}" @selected(request('criticidad') == $c)>{{ $c }}</option>
                        @endforeach
                    </select>

                    <select name="estado" 
                            class="flex-grow min-w-[150px] rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Estado --</option>
                        @foreach($estados as $e)
                            <option value="{{ $e }}" @selected(request('estado') == $e)>{{ $e }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 font-medium rounded-md transition-all duration-200 ease-in-out">
                    🔍 Filtrar
                </button>
            </form>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="p-4  px-4 py-2 text-gray-500 rounded-md bg-green-50 dark:bg-green-900 border-l-4 border-green-500 dark:border-green-300">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400 dark:text-green-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-100">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tabla --}}
<div class="w-full overflow-x-auto rounded-lg shadow ring-1 ring-black ring-opacity-5">
    <table class="w-full min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
        <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
                @foreach (['Nombre', 'Componente', 'Criticidad', 'CVSS', 'Estado', 'Fecha', 'Acciones'] as $col)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        {{ $col }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            @forelse ($vulnerabilidades as $v)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 even:bg-gray-50 dark:even:bg-gray-800 transition-colors duration-150">
                <td class="px-4 py-2 text-gray-500 whitespace-nowrap text-blue-600 dark:text-blue-400 underline cursor-pointer truncate">
                    <a href="{{ route('vulnerabilidades.show', $v->id) }}">{{ $v->nombre }}</a>
                </td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300 truncate">{{ $v->componente_afectado }}</td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $v->criticidad }}</td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $v->cvss }}</td>
                <td class="px-6 py-4">
                    <span class="px-6 py-4 text-gray-700 dark:text-gray-300 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @class([
                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' => $v->estado === 'Detectada',
                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' => $v->estado === 'En evaluación',
                            'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' => $v->estado === 'Corregida',
                            'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' => $v->estado === 'En cuarentena',
                            'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' => $v->estado === 'Falso positivo',
                        ])
                    ">
                        {{ $v->estado }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $v->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end space-x-2">
                        <!-- Botón Editar -->
                        <a href="{{ route('vulnerabilidades.edit', $v->id) }}"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm px-4 py-2 text-gray-600 dark:text-gray-200 border bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Editar
                        </a>
                        
                        <!-- Botón Eliminar -->
                        <form action="{{ route('vulnerabilidades.destroy', $v->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('¿Confirmas que deseas eliminar esta vulnerabilidad?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    No se han registrado vulnerabilidades.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>


        {{-- Paginación --}}
        <div class="mt-6">
            {{ $vulnerabilidades->links() }}
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