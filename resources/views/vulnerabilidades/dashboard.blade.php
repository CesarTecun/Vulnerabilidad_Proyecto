<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            🛡️ Dashboard de Vulnerabilidades
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Botones de acción --}}
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Acciones rápidas</h3>
                <div class="flex gap-2">
                    <a href="{{ route('vulnerabilidades.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">
                        ➕ Nueva vulnerabilidad
                    </a>

                    <form method="POST" action="{{ route('vulnerabilidades.simular') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                            🧪 Simular escaneo
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tarjetas resumen --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @php
                    $cards = [
                        ['label' => 'Total', 'value' => $total, 'color' => 'white', 'text' => 'black'],
                        ['label' => 'Críticas', 'value' => $altas, 'color' => 'red-100', 'text' => 'red-800'],
                        ['label' => 'Cuarentena', 'value' => $cuarentena, 'color' => 'yellow-100', 'text' => 'yellow-800'],
                        ['label' => 'Corregidas', 'value' => $corregidas, 'color' => 'green-100', 'text' => 'green-800'],
                        ['label' => 'Falsos Positivos', 'value' => $falsos_positivos, 'color' => 'purple-100', 'text' => 'purple-800'],
                    ];
                @endphp

                @foreach ($cards as $card)
                    <div class="bg-{{ $card['color'] }} dark:bg-gray-800 p-5 rounded-xl shadow hover:shadow-lg transition text-center">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300 uppercase">{{ $card['label'] }}</h3>
                        <p class="text-3xl font-bold text-{{ $card['text'] }} dark:text-white">{{ $card['value'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Gráfico circular --}}
            @if($porEstado && $porEstado->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 p-6 shadow rounded-xl">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">📊 Distribución por Estado</h2>
                <div class="relative h-96">
                    <canvas id="chartEstado"></canvas>
                </div>
            </div>
            @endif

            {{-- Últimas vulnerabilidades --}}
            <div class="bg-white dark:bg-gray-800 p-6 shadow rounded-xl">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">🕵️ Últimas Vulnerabilidades</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                @foreach (['Nombre', 'Componente', 'Estado', 'CVSS', 'Fecha'] as $col)
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    {{ $col }}
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($ultimas as $vuln)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $vuln->nombre }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $vuln->componente_afectado }}</td>
                                    <td class="px-4 py-2 text-sm">
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
                                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-gray-100">{{ $vuln->fecha_deteccion?->format('d/m/Y') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
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


@push('styles')
<style>
    /* Asegura que las leyendas del gráfico sean blancas en modo oscuro */
    .dark .chartjs-render-monitor + div ul li span {
        color: #ffffff !important;
    }

    .dark .chartjs-render-monitor + div ul li {
        color: #ffffff !important;
    }
</style>
@endpush

    {{-- ChartJS --}}
    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chartEstadoInstance = null;

    function getLegendColor() {
        return document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000';
    }

    function renderChartEstado() {
        const ctx = document.getElementById('chartEstado').getContext('2d');

        const data = {
            labels: {!! json_encode($porEstado?->keys() ?? []) !!},
            datasets: [{
                data: {!! json_encode($porEstado?->values() ?? []) !!},
                backgroundColor: [
                    '#ef4444', // Detectada
                    '#facc15', // Corregida
                    '#10b981', // En evaluación
                    '#3b82f6', // Falso positivo
                    '#a855f7', // En cuarentena
                ],
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: getLegendColor(),
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        };

        if (chartEstadoInstance) {
            chartEstadoInstance.destroy(); // Destruye gráfico anterior si existe
        }

        chartEstadoInstance = new Chart(ctx, config);
    }

    // Inicializa el gráfico al cargar
    renderChartEstado();

    // Observador de cambios en <html class="dark">
    const observer = new MutationObserver(() => {
        renderChartEstado(); // Redibuja el gráfico con el color actualizado
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
</script>
@endpush

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