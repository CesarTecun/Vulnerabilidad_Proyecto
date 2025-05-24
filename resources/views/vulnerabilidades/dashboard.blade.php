<x-app-layout>
    <x-slot name="header">
        {{-- Encabezado del Dashboard --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg"></div>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                        Dashboard de Vulnerabilidades
                    </h2>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Última actualización: {{ now()->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 justify-center"">

            {{-- Tarjetas Resumen --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                @foreach ([
                    ['Total', $total, 'gray'],
                    ['Críticas', $altas, 'red'],
                    ['Cuarentena', $cuarentena, 'yellow'],
                    ['Corregidas', $corregidas, 'green'],
                    ['Falsos Positivos', $falsos_positivos, 'purple']
                ] as [$label, $value, $color])
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $value }}</p>
                    </div>
                @endforeach
            </div>

{{-- Acciones Rápidas --}}
<div class="flex flex-wrap gap-4 justify-center items-center">
    <a href="{{ route('patrones.index') }}"
       class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-xl text-sm font-medium">
        Gestionar Patrones
    </a>

    <form method="POST" action="{{ route('vulnerabilidades.simular') }}">
        @csrf
        <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium">
            Simular Escaneo
        </button>
    </form>
</div>


            {{-- Gráfico por Estado --}}
            @if($porEstado && $porEstado->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 justify-center">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Distribución por Estado</h3>
                        <div class="flex gap-2">
                            <button id="chartToggle" class="text-xs bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-300 px-2 py-1 rounded">Mostrar tabla</button>
                            <select id="chartTimeRange" class="text-xs border rounded px-2 py-1 dark:bg-gray-700 dark:text-gray-300">
                                <option value="7">7 días</option>
                                <option value="30" selected>30 días</option>
                                <option value="90">3 meses</option>
                            </select>
                        </div>
                    </div>

            <div class="flex flex-row gap-4 justify-center">
                {{-- ░░ GRÁFICO (Izquierda) ░░ --}}
                <div id="chartContainer"
                    class="w-full lg:w-1/2 h-64 mx-auto relative flex items-center justify-center">
                    <div id="loadingChart"
                        class="absolute z-0 text-gray-400 dark:text-gray-500">Cargando...</div>
                    <canvas id="chartEstado"
                            width="400" height="400"
                            class="relative z-10"></canvas>
                </div>

                {{-- ░░ TABLA (Derecha) ░░ --}}
                <div id="tableContainer"
                    class="w-full lg:w-1/2 mx-auto overflow-auto hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-left">
                            <tr>
                                <th class="p-2 text-gray-800 dark:text-gray-300">Estado</th>
                                <th class="p-2 text-gray-800 dark:text-gray-200">Cantidad</th>
                                <th class="p-2 text-gray-800 dark:text-gray-200">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalCount = $porEstado->sum(); @endphp
                            @foreach($porEstado as $estado => $cantidad)
                                <tr class="border-t dark:border-gray-700">
                                    <td class="p-2 text-gray-700 dark:text-gray-200">{{ $estado }}</td>
                                    <td class="p-2 text-gray-700 dark:text-gray-200">{{ $cantidad }}</td>
                                    <td class="p-2 text-gray-700 dark:text-gray-200">
                                        {{ round(($cantidad / $totalCount) * 100, 1) }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

                </div>
            @endif

            {{-- Últimas Vulnerabilidades --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">Últimas Vulnerabilidades</h3>
                <div class="overflow-auto">
                    <table class="w-full text-sm table-auto">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="p-2 text-left text-gray-800 dark:text-gray-300">#</th>
                                <th class="p-2 text-left text-gray-800 dark:text-gray-300">Nombre</th>
                                <th class="p-2 text-left text-gray-800 dark:text-gray-300">Componente</th>
                                <th class="p-2 text-left text-gray-800 dark:text-gray-200">Estado</th>
                                <th class="p-2 text-left text-gray-800 dark:text-gray-300">CVSS</th>
                                <th class="p-2 text-left text-gray-800 dark:text-gray-300">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($ultimas as $i => $vuln)
                                <tr>
                                    <td class="p-2 text-gray-700 dark:text-gray-300">{{ $i + 1 }}</td>
                                    <td class="p-2 text-gray-700 dark:text-gray-300">{{ $vuln->nombre }}</td>
                                    <td class="p-2 text-gray-700 dark:text-gray-300">{{ $vuln->componente_afectado }}</td>
                                    <td class="p-2">
                                        <span class="p-2 text-left text-gray-800 dark:text-gray-300 rounded text-xs @class([
                                            'bg-red-100 text-red-700 dark:bg-red-500 dark:text-white' => $vuln->estado === 'Detectada',
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-400 dark:text-gray-900' => $vuln->estado === 'En evaluación',
                                            'bg-green-100 text-green-700 dark:bg-green-500 dark:text-white' => $vuln->estado === 'Corregida',
                                            'bg-blue-100 text-blue-700 dark:bg-blue-500 dark:text-white' => $vuln->estado === 'En cuarentena',
                                            'bg-purple-100 text-purple-700 dark:bg-purple-500 dark:text-white' => $vuln->estado === 'Falso positivo',
                                        ])">{{ $vuln->estado }}</span>
                                    </td>
                                    <td class="p-2 text-gray-700 dark:text-gray-300">{{ $vuln->cvss }}</td>
                                    <td class="p-2 text-gray-700 dark:text-gray-300">{{ $vuln->fecha_deteccion?->format('d/m/Y') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-4 text-center text-gray-400">No hay registros</td></tr>
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
    document.addEventListener('DOMContentLoaded', function () {
        const canvas = document.getElementById('chartEstado');
        const loader = document.getElementById('loadingChart');
        const chartContainer = document.getElementById('chartContainer');
        const tableContainer = document.getElementById('tableContainer');
        const toggleBtn = document.getElementById('chartToggle');

        if (!canvas || !canvas.getContext) { loader.innerText = "Error cánvas no encontrado"; return; }
        const ctx = canvas.getContext('2d');
        const labels = {!! json_encode(array_keys($porEstado->toArray())) !!};
        const values = {!! json_encode(array_values($porEstado->toArray())) !!};
        if (!labels.length || !values.length) { loader.innerText = "No hay datos para graficar."; return; }

        // Destruir instancia previa
        if (window.chartEstadoInstance) {
            window.chartEstadoInstance.destroy();
        }

        window.chartEstadoInstance = new Chart(ctx, {
            type: 'doughnut',
            data: { labels: labels, datasets: [{ data: values, backgroundColor: ['#ef4444','#f59e0b','#10b981','#3b82f6','#8b5cf6'], borderWidth: 1 }] },
            options: { responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'right', labels: { color: document.documentElement.classList.contains('dark') ? '#e5e7eb' : '#374151' } } }
            }
        });

        loader.style.display = 'none';

        toggleBtn?.addEventListener('click', function() {
            const showing = !chartContainer.classList.contains('hidden');
            chartContainer.classList.toggle('hidden', showing);
            tableContainer.classList.toggle('hidden', !showing);
            this.innerText = showing ? 'Mostrar gráfico' : 'Mostrar tabla';
        });
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