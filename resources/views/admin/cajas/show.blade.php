<x-layouts::app title="Detalle de la Caja #{{ $caja->id }}">
    <div class="relative mb-6 w-full flex justify-between items-center">
        <div>
            <flux:heading size="xl" level="1">Detalle de la Caja #{{ $caja->id }}</flux:heading>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Revisa el resumen financiero y las ventas asociadas a este turno.</p>
        </div>
        <div>
            <a href="{{ route('admin.cajas.index') }}"
                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    <flux:separator variant="subtle" class="mb-6" />

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="p-5 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Saldo Inicial</p>
                    <h3 class="text-2xl font-extrabold text-gray-800 dark:text-white mt-1">{{ $simboloMoneda ?? 'Bs.' }} {{ number_format($caja->saldo_inicial, 2) }}</h3>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-lg">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <div class="p-5 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Vendido</p>
                    <h3 class="text-2xl font-extrabold text-green-600 dark:text-green-400 mt-1">{{ $simboloMoneda ?? 'Bs.' }} {{ number_format($caja->total_ventas, 2) }}</h3>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 rounded-lg">
                    <i class="fas fa-shopping-cart text-xl"></i>
                </div>
            </div>
        </div>

        <div class="p-5 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Saldo Final / Actual</p>
                    <h3 class="text-2xl font-extrabold text-amber-600 dark:text-amber-400 mt-1">
                        {{ $simboloMoneda ?? 'Bs.' }} {{ number_format($caja->saldo_final ?? ($caja->saldo_inicial + $caja->total_ventas), 2) }}
                    </h3>
                </div>
                <div class="p-3 bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 rounded-lg">
                    <i class="fas fa-coins text-xl"></i>
                </div>
            </div>
        </div>

        <div class="p-5 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Estado</p>
                    <div class="mt-1">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $caja->estado == 'abierto' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' }}">
                            {{ ucfirst($caja->estado) }}
                        </span>
                    </div>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 rounded-lg">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm mb-6">
        <flux:heading size="lg" class="mb-4">Información del Turno</flux:heading>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="font-semibold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    <i class="fas fa-user"></i> Cajero:
                </span>
                <p class="text-gray-800 dark:text-gray-200 mt-1 font-medium">{{ $caja->user->name ?? 'N/A' }}</p>
            </div>
            <div>
                <span class="font-semibold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    <i class="fas fa-calendar-alt"></i> Fecha de Apertura:
                </span>
                <p class="text-gray-800 dark:text-gray-200 mt-1 font-medium">{{ $caja->fecha_apertura }}</p>
            </div>
            <div>
                <span class="font-semibold text-gray-500 dark:text-gray-400 flex items-center gap-1">
                    <i class="fas fa-calendar-check"></i> Fecha de Cierre:
                </span>
                <p class="text-gray-800 dark:text-gray-200 mt-1 font-medium">{{ $caja->fecha_cierre ?? 'Caja en curso (Aún abierta)' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-zinc-700 flex justify-between items-center">
            <flux:heading size="lg">Ventas Realizadas en este Turno</flux:heading>
        </div>

        @php
            // Obtenemos las ventas del turno cargando únicamente la relación 'detalles.producto' y 'cliente'
            $ventas = \App\Models\Venta::with(['cliente', 'detalles.producto'])
                ->where(function($query) use ($caja) {
                    $query->where('caja_id', $caja->id)
                          ->orWhere(function($q) use ($caja) {
                              $q->where('user_id', $caja->user_id)
                                ->whereNull('caja_id')
                                ->whereBetween('fecha_venta', [$caja->fecha_apertura, $caja->fecha_cierre ?? now()]);
                          });
                })->get();
        @endphp

        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                    <tr>
                        <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nro</th>
                        <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Comprobante</th>
                        <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                        <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                        <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Productos</th>
                        <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo Pago</th>
                        <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800">
                    @forelse($ventas as $index => $venta)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                            <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $index + 1 }}</td>
                            
                            <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center font-medium">{{ $venta->nro_comprobante ?? $venta->comprobante ?? 'N/A' }}</td>
                            
                            <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $venta->fecha_venta ?? $venta->created_at }}</td>
                            
                            <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center font-medium">
                                {{ $venta->cliente->nombres ?? ($venta->cliente->nombre ?? ($venta->cliente_nombre ?? 'General')) }}
                            </td>
                            
                            <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm">
                                <ul class="list-disc list-inside text-xs text-gray-700 dark:text-gray-300 text-left">
                                    @if($venta->detalles && $venta->detalles->count() > 0)
                                        @foreach($venta->detalles as $detalle)
                                            <li>
                                                <span class="font-semibold">{{ $detalle->producto->nombre ?? ($detalle->nombre_producto ?? 'Producto') }}</span> 
                                                (Cant: {{ $detalle->cantidad }})
                                            </li>
                                        @endforeach
                                    @else
                                        <span class="text-gray-400 italic">Sin detalle registrado</span>
                                    @endif
                                </ul>
                            </td>

                            <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $venta->tipo_pago ?? 'Efectivo' }}</td>
                            
                            <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center font-semibold text-green-600 dark:text-green-400">
                                {{ $simboloMoneda ?? 'Bs.' }} {{ number_format($venta->total, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No se registraron ventas en este turno de caja.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>