<x-app-layout> 
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
            Dashboard de Vulnerabilidades
        </h2>
    </x-slot>


    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        {{-- Botones de acción --}}
                <div class="flex justify-end gap-2">
                    <a href="{{ route('vulnerabilidades.create') }}" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                        ➕ Nueva vulnerabilidad
                    </a>

                    <form method="POST" action="{{ route('vulnerabilidades.simular') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 
                                bg-indigo-600 text-white 
                                dark:bg-indigo-500 dark:text-white 
                                hover:bg-indigo-700 dark:hover:bg-indigo-600 
                                text-sm font-medium rounded transition">
                            🧪 Simular escaneo
                        </button>

                    </form>
                </div>

            {{-- Tarjetas resumen --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">

            <div class="bg-white dark:bg-gray-800 p-4 shadow rounded text-center">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total</h3>
                <p class="text-3xl text-black dark:text-white">{{ $total }}</p>
            </div>

            <div class="bg-red-100 dark:bg-red-800 p-4 shadow rounded text-center">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Críticas</h3>
                <p class="text-3xl text-red-900 dark:text-white">{{ $altas }}</p>
            </div>

            <div class="bg-yellow-100 dark:bg-yellow-700 p-4 shadow rounded text-center">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-100">Cuarentena</h3>
                <p class="text-3xl text-yellow-900 dark:text-white">{{ $cuarentena }}</p>
            </div>

            <div class="bg-green-100 dark:bg-green-700 p-4 shadow rounded text-center">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-100">Corregidas</h3>
                <p class="text-3xl text-green-900 dark:text-white">{{ $corregidas }}</p>
            </div>

            <div class="bg-purple-100 dark:bg-purple-700 p-4 shadow rounded text-center">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-100">Falsos Positivos</h3>
                <p class="text-3xl text-purple-900 dark:text-white">{{ $falsos_positivos }}</p>
            </div>

            </div>


            {{-- Gráfico circular --}}
        @if($porEstado && $porEstado->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 p-6 shadow rounded">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Distribución por Estado</h2>
            
            <!-- Contenedor que controla el tamaño -->
            <div style="position: relative; height: 600px; width: 100%;">
            <canvas id="chartEstado"></canvas>
            </div>

        </div>
        @endif
        {{-- Últimas vulnerabilidades --}}
<div class="bg-white dark:bg-gray-800 p-6 shadow rounded">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Últimas Vulnerabilidades</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Nombre</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Componente</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Estado</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">CVSS</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Fecha</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($ultimas as $vuln)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $vuln->nombre }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $vuln->componente_afectado }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">
                        <span class="inline-block px-2 py-1 rounded text-xs font-semibold
                            @class([
                                'bg-red-100 text-red-800 dark:bg-red-700 dark:text-white' => $vuln->estado === 'Detectada',
                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-white' => $vuln->estado === 'En evaluación',
                                'bg-green-100 text-green-800 dark:bg-green-600 dark:text-white' => $vuln->estado === 'Corregida',
                                'bg-blue-100 text-blue-800 dark:bg-blue-600 dark:text-white' => $vuln->estado === 'En cuarentena',
                                'bg-purple-100 text-purple-800 dark:bg-purple-600 dark:text-white' => $vuln->estado === 'Falso positivo',
                            ])
                        ">
                            {{ $vuln->estado }}
                        </span>

                        </td>
                        <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $vuln->cvss }}</td>
                        <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $vuln->fecha_deteccion ? $vuln->fecha_deteccion->format('d/m/Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            No hay vulnerabilidades registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


        </div>
    </div>
    

    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartEstado').getContext('2d');

    const data = {
        labels: {!! json_encode($porEstado?->keys() ?? []) !!},
        datasets: [{
            data: {!! json_encode($porEstado?->values() ?? []) !!},
            backgroundColor: [
                '#ef4444',
                '#facc15',
                '#10b981',
                '#3b82f6',
                '#a855f7',
            ],
        }]
    };

    const config = {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false, // ✅ esto activa el contenedor con altura fija
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#000' // ajusta si usas dark mode
                    }
                }
            }
        }
    };

    new Chart(ctx, config);
</script>
@endpush

</x-app-layout>

