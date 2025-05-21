<x-app-layout>
<x-slot name="header">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <span class="text-2xl">🛡️</span>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    Dashboard de Vulnerabilidades
                </h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                <span class="inline-flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                    Última actualización: {{ now()->format('d/m/Y H:i') }}
                </span>
            </p>
        </div>
        <div class="flex gap-2">
        </div>
    </div>
</x-slot>


    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Acciones rápidas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
    <a href="{{ route('vulnerabilidades.create') }}"
        class="bg-gradient-to-br from-blue-600 to-blue-500 text-white rounded-xl p-5 flex flex-col items-center justify-center hover:from-blue-700 hover:to-blue-600 transition-all duration-300 ease-in-out shadow hover:shadow-md">
        <div class="p-3 bg-white/20 rounded-full mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
        </div>
        <div class="text-lg font-semibold">Nueva vulnerabilidad</div>
        <div class="text-xs opacity-80 mt-1">Agregar manualmente</div>
    </a>

        <form method="POST" action="{{ route('vulnerabilidades.simular') }}">
            @csrf
            <button type="submit" aria-label="Simular escaneo"
                class="w-full bg-gradient-to-br from-indigo-600 to-indigo-500 text-white rounded-xl p-5 hover:from-indigo-700 hover:to-indigo-600 transition-all duration-300 ease-in-out shadow hover:shadow-md flex flex-col items-center justify-center">
                <div class="p-3 bg-white/20 rounded-full mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="text-lg font-semibold">Simular escaneo</div>
                <div class="text-xs opacity-80 mt-1">Ejecutar prueba</div>
            </button>
        </form>
    </div>

             {{-- Tarjetas resumen --}}
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @php
                $cards = [
                    ['label' => 'Total', 'value' => $total, 'color' => 'gray', 'icon' => '
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    '],
                    ['label' => 'Críticas', 'value' => $altas, 'color' => 'red', 'icon' => '
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    '],
                    ['label' => 'Cuarentena', 'value' => $cuarentena, 'color' => 'yellow', 'icon' => '
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    '],
                    ['label' => 'Corregidas', 'value' => $corregidas, 'color' => 'green', 'icon' => '
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    '],
                    ['label' => 'Falsos Positivos', 'value' => $falsos_positivos, 'color' => 'purple', 'icon' => '
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    '],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow hover:shadow-lg transition-all duration-300 ease-in-out">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $card['label'] }}</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $card['value'] }}</p>
                        </div>
                        <div class="p-2 rounded-lg bg-{{ $card['color'] }}-100 dark:bg-{{ $card['color'] }}-900/50 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-300">
                            {!! $card['icon'] !!}
                        </div>
                    </div>
                    @if($loop->first)
                        <div class="mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1"></span>
                                <span class="font-medium text-gray-700 dark:text-gray-300">3 nuevas</span> esta semana
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

            {{-- Gráfico circular --}}
            @if($porEstado && $porEstado->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 p-6 shadow rounded-xl">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">📊 Distribución por Estado</h2>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                        <button id="chartToggle" class="px-3 py-1 text-xs bg-gray-100 dark:bg-gray-650 rounded-md">
                            Mostrar tabla
                        </button>
                        <select id="chartTimeRange" class="text-xs border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-650 px-2 py-1">
                            <option value="7">Últimos 7 días</option>
                            <option value="30" selected>Últimos 30 días</option>
                            <option value="90">Últimos 3 meses</option>
                            <option value="365">Último año</option>
                        </select>
                    </div>

                </div>
                
                <div class="relative">
                    <div id="chartContainer" class="relative h-96 flex items-center justify-center">
                        <div id="loadingChart" class="absolute text-gray-400 dark:text-gray-500 z-0 flex flex-col items-center">
                            <svg class="animate-spin h-8 w-8 text-gray-400 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Cargando gráfico...
                        </div>
                        <canvas id="chartEstado" class="relative z-10"></canvas>
                    </div>
                    
                    <div id="tableContainer" class="hidden overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cantidad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @php $total = $porEstado->sum() @endphp
                                @foreach($porEstado as $estado => $cantidad)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $estado }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $cantidad }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ round(($cantidad / $total) * 100, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

            {{-- Últimas vulnerabilidades --}}
            <div class="bg-white dark:bg-gray-800 p-6 shadow rounded-xl">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">🕵️ Últimas Vulnerabilidades</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">#</th>
                                @foreach (['Nombre', 'Componente', 'Estado', 'CVSS', 'Fecha'] as $col)
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        {{ $col }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($ultimas as $index => $vuln)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition even:bg-gray-50 dark:even:bg-gray-900">
                                    <td class="px-4 py-2 text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $vuln->nombre }}</td>
                                    <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $vuln->componente_afectado }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @class([
                                            'bg-red-100 text-red-700 dark:bg-red-500 dark:text-white' => $vuln->estado === 'Detectada',
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-400 dark:text-gray-900' => $vuln->estado === 'En evaluación',
                                            'bg-green-100 text-green-700 dark:bg-green-500 dark:text-white' => $vuln->estado === 'Corregida',
                                            'bg-blue-100 text-blue-700 dark:bg-blue-500 dark:text-white' => $vuln->estado === 'En cuarentena',
                                            'bg-purple-100 text-purple-700 dark:bg-purple-500 dark:text-white' => $vuln->estado === 'Falso positivo',
                                        ])
                                        ">
                                            {{ $vuln->estado }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $vuln->cvss }}</td>
                                    <td class="px-4 py-2 text-gray-800 dark:text-gray-100">{{ $vuln->fecha_deteccion?->format('d/m/Y') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
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
    /* Ajuste del texto de leyendas Chart.js en modo oscuro */
    .dark .chartjs-render-monitor + div ul li span,
    .dark .chartjs-render-monitor + div ul li {
        color: #ffffff !important;
    }

    /* 🎨 Estilos personalizados de texto */
    .text-titulo {
        color: #1f2937; /* gray-800 */
    }
    .text-subtitulo {
        color: #374151; /* gray-700 */
    }
    .text-muted {
        color: #6b7280; /* gray-500 */
    }

    /* En modo oscuro */
    .dark .text-titulo {
        color: #f9fafb; /* casi blanco */
    }
    .dark .text-subtitulo {
        color: #d1d5db; /* gris claro */
    }
    .dark .text-muted {
        color: #9ca3af; /* gris medio */
    }
</style>
@endpush


    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chartEstadoInstance = null;
    let chartData = {
        labels: {!! json_encode($porEstado?->keys() ?? []) !!},
        datasets: [{
            data: {!! json_encode($porEstado?->values() ?? []) !!},
            backgroundColor: [
                '#ef4444', // red
                '#f59e0b', // amber
                '#10b981', // emerald
                '#3b82f6', // blue
                '#8b5cf6'  // violet
            ],
            borderWidth: 0
        }]
    };

    function getLegendColor() {
        return document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151';
    }

    function renderChartEstado() {
        const ctx = document.getElementById('chartEstado').getContext('2d');
        
        // Ocultar mensaje de carga
        document.getElementById('loadingChart').style.display = 'none';
        
        if (chartEstadoInstance) {
            chartEstadoInstance.destroy();
        }

        chartEstadoInstance = new Chart(ctx, {
            type: 'doughnut',
            data: chartData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: getLegendColor(),
                            font: {
                                size: 12
                            },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: document.documentElement.classList.contains('dark') ? '#1f2937' : '#ffffff',
                        titleColor: document.documentElement.classList.contains('dark') ? '#ffffff' : '#111827',
                        bodyColor: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151',
                        borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    }

    // Toggle entre gráfico y tabla
    document.getElementById('chartToggle').addEventListener('click', function() {
        const chartContainer = document.getElementById('chartContainer');
        const tableContainer = document.getElementById('tableContainer');
        
        if (chartContainer.classList.contains('hidden')) {
            chartContainer.classList.remove('hidden');
            tableContainer.classList.add('hidden');
            this.textContent = 'Mostrar tabla';
            renderChartEstado();
        } else {
            chartContainer.classList.add('hidden');
            tableContainer.classList.remove('hidden');
            this.textContent = 'Mostrar gráfico';
        }
    });

    // Cambiar rango de tiempo (simulado)
    document.getElementById('chartTimeRange').addEventListener('change', function() {
        document.getElementById('loadingChart').style.display = 'flex';
        document.getElementById('chartEstado').style.display = 'none';
        
        // Simular carga de datos
        setTimeout(() => {
            // En una aplicación real, aquí harías una petición AJAX
            document.getElementById('loadingChart').style.display = 'none';
            document.getElementById('chartEstado').style.display = 'block';
            renderChartEstado();
        }, 1000);
    });

    // Observar cambios de tema oscuro/claro
    const observer = new MutationObserver(renderChartEstado);
    observer.observe(document.documentElement, { 
        attributes: true, 
        attributeFilter: ['class'] 
    });

    // Renderizar el gráfico al cargar
    document.addEventListener('DOMContentLoaded', function() {
        renderChartEstado();
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