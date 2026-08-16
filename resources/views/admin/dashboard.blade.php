<x-layouts::app title="Dashboard">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Dashboard</flux:heading>
        <p class="text-sm text-gray-500 dark:text-zinc-400 mt-1">Bienvenido al sistema de gestión y ventas.</p>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <!-- Tarjetas de Estadísticas / KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Tarjeta Productos -->
        <div class="p-4 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Total Productos</p>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ number_format($totalProductos) }}</h4>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 rounded-lg">
                    <i class="fas fa-cube text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Tarjeta Clientes -->
        <div class="p-4 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Total Clientes</p>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ number_format($totalClientes) }}</h4>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-300 rounded-lg">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Tarjeta Ventas del Día -->
        <div class="p-4 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Ventas del Día</p>
                    <h4 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ $simboloMoneda }} {{ number_format($ventasHoy, 2) }}</h4>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-300 rounded-lg">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Tarjeta Ventas del Mes -->
        <div class="p-4 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-zinc-400 uppercase tracking-wider">Ventas del Mes ({{ $cantidadVentasMes }})</p>
                    <h4 class="text-xl font-bold text-gray-800 dark:text-white mt-1">{{ $simboloMoneda }} {{ number_format($ventasMes, 2) }}</h4>
                </div>
                <div class="p-3 bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-300 rounded-lg">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Secciones de Tablas y Reportes Rápidos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        
        <!-- Últimas Ventas -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-4 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <flux:heading size="lg">Últimas Ventas</flux:heading>
                <a href="{{ route('admin.ventas.index') }}" class="text-xs text-blue-500 hover:underline font-semibold">Ver todas</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                        <tr>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Cliente</th>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Total</th>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Fecha</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800">
                        @forelse($ultimasVentas as $venta)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm font-semibold">{{ $venta->cliente->nombres ?? 'General' }}</td>
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $simboloMoneda }} {{ number_format($venta->total, 2) }}</td>
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $venta->fecha_venta }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-center text-sm text-gray-500">No hay ventas recientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Productos con Stock Bajo -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-4 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <flux:heading size="lg">Productos con Stock Bajo</flux:heading>
                <a href="{{ route('admin.productos.index') }}" class="text-xs text-blue-500 hover:underline font-semibold">Ver inventario</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                        <tr>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Producto</th>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Stock Actual</th>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Precio</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800">
                        @forelse($productosBajosStock as $prod)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm font-semibold">{{ $prod->nombre }}</td>
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">
                                    <span class="px-2 py-0.5 text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 rounded-full">
                                        {{ $prod->stock }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $simboloMoneda }} {{ number_format($prod->precio_venta, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-center text-sm text-gray-500">Excelente, no hay productos con stock bajo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Fila inferior: Productos más vendidos y Mejores Clientes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Productos más vendidos -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-4 shadow-sm">
            <flux:heading size="lg" class="mb-4">Productos Más Vendidos</flux:heading>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                        <tr>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Producto</th>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Cantidad Vendida</th>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Ingreso Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800">
                        @forelse($productosMasVendidos as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm font-semibold">{{ $item->nombre }}</td>
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $item->total_cantidad }}</td>
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $simboloMoneda }} {{ number_format($item->total_ingreso, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-center text-sm text-gray-500">Sin registros de ventas aún.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mejores Clientes -->
        <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-4 shadow-sm">
            <flux:heading size="lg" class="mb-4">Mejores Clientes</flux:heading>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                        <tr>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Cliente</th>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Compras</th>
                            <th class="px-3 py-2 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Total Gastado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800">
                        @forelse($mejoresClientes as $cliente)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm font-semibold">{{ $cliente->nombres }}</td>
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $cliente->ventas_count }}</td>
                                <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $simboloMoneda }} {{ number_format($cliente->ventas_sum_total ?? 0, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-center text-sm text-gray-500">Sin datos de clientes frecuentes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts::app>