<x-layouts::app title="Dashboard">
    <!-- Carga de Chart.js desde CDN para las gráficas -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Cabecera con Ubicación y Fecha Actual -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-4 border-b border-gray-200 dark:border-zinc-700 gap-4">
        <div>
            <flux:heading size="xl" level="1">Dashboard</flux:heading>
            <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Resumen general del sistema</p>
        </div>
        <div class="flex items-center gap-4 text-xs font-medium text-gray-600 dark:text-zinc-300 bg-white dark:bg-zinc-800 px-4 py-2 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
            <div class="flex items-center gap-1.5">
                <i class="fas fa-map-marker-alt text-red-500"></i>
                <span>Santa Cruz de la Sierra, Bolivia 🇧🇴</span>
            </div>
            <div class="h-4 w-px bg-gray-300 dark:bg-zinc-700"></div>
            <div class="flex items-center gap-1.5">
                <i class="fas fa-calendar-alt text-blue-500"></i>
                <span>{{ now()->format('d de F de Y | h:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas / KPIs Superiores -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Ventas del Día -->
        <div class="p-4 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Ventas del Día</p>
                    <h4 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ $simboloMoneda }} {{ number_format($ventasHoy, 2) }}</h4>
                </div>
                <div class="p-3 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-300 rounded-lg">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
            </div>
            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                <i class="fas fa-arrow-up"></i> Actividad de hoy
            </span>
        </div>

        <!-- Ventas del Mes -->
        <div class="p-4 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Ventas del Mes</p>
                    <h4 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ $simboloMoneda }} {{ number_format($ventasMes, 2) }}</h4>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 rounded-lg">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
            <span class="text-xs text-blue-600 dark:text-blue-400 font-semibold">
                {{ $cantidadVentasMes }} transacciones registradas
            </span>
        </div>

        <!-- Clientes -->
        <div class="p-4 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Clientes</p>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ number_format($totalClientes) }}</h4>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-300 rounded-lg">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <span class="text-xs text-purple-600 dark:text-purple-400 font-semibold">Registros activos</span>
        </div>

        <!-- Productos -->
        <div class="p-4 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Productos</p>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ number_format($totalProductos) }}</h4>
                </div>
                <div class="p-3 bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-300 rounded-lg">
                    <i class="fas fa-cube text-xl"></i>
                </div>
            </div>
            <span class="text-xs text-amber-600 dark:text-amber-400 font-semibold">Catálogo general</span>
        </div>
    </div>

    <!-- Sección de Gráficos (Resumen de Ventas y Métodos de Pago) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <!-- Gráfica de Líneas: Resumen de Ventas -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center mb-4">
                <flux:heading size="lg">Resumen de Ventas (Últimos 7 días)</flux:heading>
                <span class="text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 px-2.5 py-1 rounded-md font-semibold">Tendencia</span>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="graficoVentas"></canvas>
            </div>
        </div>

        <!-- Gráfica de Dona: Ventas por Método de Pago con Desglose -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-5 shadow-sm flex flex-col justify-between">
            <div class="mb-2">
                <flux:heading size="lg">Ventas por Método de Pago</flux:heading>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-4 my-auto">
                <!-- Contenedor de la Dona con el Total al Centro -->
                <div class="relative h-52 w-full flex items-center justify-center">
                    <canvas id="graficoMetodos"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                        <span class="text-[11px] text-gray-400 font-medium">Total</span>
                        <span class="text-xs font-bold text-gray-800 dark:text-white">{{ $simboloMoneda }} {{ number_format($totalGeneralMetodos, 0) }}</span>
                    </div>
                </div>

                <!-- Lista de Detalle Lateral -->
                <div class="flex flex-col space-y-2.5">
                    @forelse($metodosDetalle as $item)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $item['color'] }};"></span>
                                <span class="font-semibold text-gray-700 dark:text-zinc-300">{{ $item['nombre'] }}</span>
                            </div>
                            <div class="text-right">
                                <span class="block font-bold text-gray-800 dark:text-white">{{ $simboloMoneda }} {{ number_format($item['monto'], 2) }}</span>
                                <span class="text-[10px] text-gray-400">{{ $item['porcentaje'] }}%</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center">Sin registros de pagos.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- Fila central: Stock Bajo (Lado derecho en imagen de referencia o adaptable) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <!-- Últimas Ventas -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-4 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <flux:heading size="lg">Últimas Ventas</flux:heading>
                <a href="{{ route('admin.ventas.index') }}" class="text-xs text-blue-500 hover:underline font-semibold">Ver todas</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <tbody class="divide-y divide-gray-100 dark:divide-zinc-700">
                        @forelse($ultimasVentas as $venta)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                <td class="py-2.5 px-2 text-sm">
                                    <span class="font-bold text-gray-800 dark:text-white block">VTA-{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="py-2.5 px-2 text-sm text-center">
                                    <span class="block text-gray-600 dark:text-zinc-300 font-medium">{{ $venta->cliente->nombres ?? 'General' }}</span>
                                </td>
                                <td class="py-2.5 px-2 text-sm text-right font-bold text-emerald-600">
                                    {{ $simboloMoneda }} {{ number_format($venta->total, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-sm text-gray-500">Sin ventas recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-4 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <flux:heading size="lg">Productos Más Vendidos</flux:heading>
                <a href="{{ route('admin.productos.index') }}" class="text-xs text-blue-500 hover:underline font-semibold">Ver todos</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <tbody class="divide-y divide-gray-100 dark:divide-zinc-700">
                        @forelse($productosMasVendidos as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                <td class="py-2.5 px-2 text-base font-extrabold text-gray-400 w-8 text-center">{{ $index + 1 }}</td>
                                <td class="py-2.5 px-2 w-12 text-center">
                                    @if(!empty($item->imagen))
                                        <img src="{{ asset('storage/' . $item->imagen) }}" alt="{{ $item->nombre }}" class="w-10 h-10 object-cover rounded-md mx-auto border border-gray-200 dark:border-zinc-700">
                                    @else
                                        <div class="w-10 h-10 bg-gray-100 dark:bg-zinc-700 rounded-md flex items-center justify-center mx-auto text-gray-400">
                                            <i class="fas fa-cube text-xs"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-2.5 px-2 text-sm">
                                    <span class="font-semibold text-gray-800 dark:text-white block">{{ $item->nombre }}</span>
                                    <span class="text-xs text-gray-500">{{ $item->total_cantidad }} unidades</span>
                                </td>
                                <td class="py-2.5 px-2 text-sm text-right font-bold text-gray-700 dark:text-zinc-300">
                                    {{ $simboloMoneda }} {{ number_format($item->total_ingreso, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-sm text-gray-500">Sin datos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Productos con Stock Bajo -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-4 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <flux:heading size="lg">Stock Bajo</flux:heading>
                <a href="{{ route('admin.productos.index') }}" class="text-xs text-blue-500 hover:underline font-semibold">Ver todos</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <tbody class="divide-y divide-gray-100 dark:divide-zinc-700">
                        @forelse($productosBajosStock as $prod)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                <td class="py-2.5 px-2 text-sm font-semibold text-gray-800 dark:text-white">
                                    {{ $prod->nombre }}
                                </td>
                                <td class="py-2.5 px-2 text-sm text-right">
                                    <span class="px-2.5 py-1 text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300 rounded-md">
                                        Stock: {{ $prod->stock }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-4 text-center text-sm text-gray-500">Inventario en óptimas condiciones.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Scripts de inicialización de Charts -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // 1. Gráfica de Líneas (Resumen de Ventas)
            const ctxVentas = document.getElementById('graficoVentas').getContext('2d');
            new Chart(ctxVentas, {
                type: 'line',
                data: {
                    labels: {!! json_encode($fechasUltimosDias) !!},
                    datasets: [{
                        label: 'Ventas ({{ $simboloMoneda }})',
                        data: {!! json_encode($ventasUltimosDias) !!},
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#10B981'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(200, 200, 200, 0.1)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // 2. Gráfica de Dona (Métodos de Pago)
            const ctxMetodos = document.getElementById('graficoMetodos').getContext('2d');
            const metodosLabels = {!! json_encode($metodosLabels) !!};
            const metodosData = {!! json_encode($metodosData) !!};
            const coloresMetodos = {!! json_encode(array_column($metodosDetalle, 'color')) !!};

            new Chart(ctxMetodos, {
                type: 'doughnut',
                data: {
                    labels: metodosLabels.length > 0 ? metodosLabels : ['Efectivo', 'QR', 'Tarjeta'],
                    datasets: [{
                        data: metodosData.length > 0 ? metodosData : [1, 1, 1],
                        backgroundColor: coloresMetodos.length > 0 ? coloresMetodos : ['#06B6D4', '#10B981', '#F59E0B'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false } // Oculto porque usamos la lista lateral personalizada
                    },
                    cutout: '72%'
                }
            });
        });
    </script>
</x-layouts::app>
