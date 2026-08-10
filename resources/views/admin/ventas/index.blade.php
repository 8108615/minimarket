<x-layouts::app title="Gestión de Ventas">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Gestión de Ventas</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
        <!-- Formulario de Búsqueda y Filtros -->
        <div class="flex-1 w-full">
            <form action="{{ route('admin.ventas.index') }}" method="GET" class="flex flex-wrap gap-2 items-center">
                <div class="w-full md:w-72">
                    <flux:input name="search" type="text" icon="magnifying-glass" placeholder="Buscar por ID, cliente..."
                        value="{{ request('search') }}" class="transition-all duration-200" />
                </div>

                <div class="w-full md:w-44">
                    <flux:select name="metodo_pago" placeholder="Método de pago">
                        <option value="">Todos los métodos</option>
                        <option value="Efectivo" {{ request('metodo_pago') == 'Efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="QR" {{ request('metodo_pago') == 'QR' ? 'selected' : '' }}>QR</option>
                        <option value="Tarjeta" {{ request('metodo_pago') == 'Tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                    </flux:select>
                </div>

                <div class="w-full md:w-40">
                    <flux:select name="estado" placeholder="Estado">
                        <option value="">Todos</option>
                        <option value="Completado" {{ request('estado') == 'Completado' ? 'selected' : '' }}>Completado</option>
                        <option value="Anulado" {{ request('estado') == 'Anulado' ? 'selected' : '' }}>Anulado</option>
                    </flux:select>
                </div>

                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-search"></i> Buscar
                </button>

                @if (request('search') || request('metodo_pago') || request('estado'))
                    <a href="{{ route('admin.ventas.index') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <!-- Botones de Acción / Exportación / Crear -->
        <div class="flex items-center gap-2 w-full md:w-auto justify-end">
            <a href="{{ route('admin.ventas.excel') }}"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('admin.ventas.pdf') }}"
                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('admin.ventas.create') }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-1"></i> Nueva Venta
            </a>
        </div>
    </div>

    @if (request('search') || request('metodo_pago') || request('estado'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-lg text-gray-700 dark:text-gray-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $ventas->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $ventas->total() }}</span>
                {{ $ventas->total() == 1 ? 'resultado' : 'resultados' }} con los filtros aplicados.
            </p>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nro</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Atendido por</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Productos</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha de Compra</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Método de Pago</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @forelse ($ventas as $venta)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <!-- Nro con loop iteration y soporte de paginación -->
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">
                            {{ $loop->iteration + ($ventas->currentPage() - 1) * $ventas->perPage() }}
                        </td>

                        <!-- Cliente -->
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm font-semibold">
                            {{ $venta->cliente->nombre ?? 'Cliente General' }}
                        </td>

                        <!-- Atendido por -->
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">
                            {{ $venta->user->name ?? 'N/A' }}
                        </td>

                        <!-- Productos que se vendió -->
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm">
                            <ul class="list-disc list-inside space-y-0.5 text-xs">
                                @foreach($venta->detalles as $detalle)
                                    <li>
                                        <span class="font-medium">{{ $detalle->producto->nombre ?? 'Producto Eliminado' }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">({{ $detalle->cantidad }} x $ {{ number_format($detalle->precio_venta, 2) }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>

                        <!-- Fecha -->
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y H:i') }}
                        </td>

                        <!-- Método de Pago -->
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300">
                                {{ $venta->metodo_pago }}
                            </span>
                        </td>

                        <!-- Total -->
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm font-bold text-right whitespace-nowrap">
                            $ {{ number_format($venta->total, 2) }}
                        </td>

                        <!-- Estado -->
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $venta->estado == 'Completado' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' }}">
                                {{ $venta->estado }}
                            </span>
                        </td>

                        <!-- Acciones -->
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.ventas.show', $venta->id) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>

                                @if($venta->estado !== 'Anulado')
                                    <form action="{{ route('admin.ventas.destroy', $venta->id) }}" method="POST" id="formVenta{{ $venta->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition cursor-pointer" onclick="confirmarAnulacion{{ $venta->id }}()">
                                            <i class="fas fa-trash-alt mr-1"></i> Anular
                                        </button>
                                    </form>
                                    <script>
                                        function confirmarAnulacion{{ $venta->id }}() {
                                            Swal.fire({
                                                title: '¿Anular venta #{{ $venta->id }}?',
                                                text: "Esta acción anulará el registro de la venta.",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonText: 'Sí, anular',
                                                confirmButtonColor: '#a5161d',
                                                cancelButtonText: 'Cancelar'
                                            }).then((result) => {
                                                if (result.isConfirmed) document.getElementById('formVenta{{ $venta->id }}').submit();
                                            });
                                        }
                                    </script>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">No se encontraron registros de ventas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($ventas->hasPages())
        <div class="px-3 mt-4">{{ $ventas->links() }}</div>
    @endif
</x-layouts::app>
