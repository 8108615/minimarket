<x-layouts::app title="Listado de Compras">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Gestión de Compras</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ route('admin.compras.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por proveedor..."
                        value="{{ request('buscar') }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ route('admin.compras.index') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="flex-1 justify-end flex gap-2">
            <a href="{{ route('admin.compras.excel') }}"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            <a href="{{ route('admin.compras.pdf') }}"
                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('admin.compras.create') }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-2"></i> Registrar Compra
            </a>
        </div>
    </div>

    @if (request('buscar'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-xl text-gray-700 dark:text-gray-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $compras->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $compras->total() }}</span>
                {{ $compras->total() == 1 ? 'resultado' : 'resultados' }}
                con la búsqueda: <span class="font-semibold">"{{ request('buscar') }}"</span>
            </p>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nro</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Proveedor</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Producto</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cantidad</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio Unitario</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @forelse ($compras as $compra)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-center align-middle">{{ $loop->iteration }}</td>
                        
                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-center font-semibold align-middle">
                            {{ $compra->proveedor->nombre ?? 'Sin proveedor' }}
                        </td>

                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-center text-gray-600 dark:text-gray-300 align-middle">
                            {{ $compra->user->name ?? 'N/D' }}
                        </td>

                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-xs text-left align-middle">
                            <div class="space-y-2">
                                @foreach($compra->detalles as $detalle)
                                    <div class="font-semibold text-gray-900 dark:text-gray-100 pb-1 border-b border-gray-100 dark:border-zinc-700/50 last:border-0 last:pb-0">
                                        {{ $detalle->producto->nombre ?? 'Producto eliminado' }}
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-xs text-center align-middle">
                            <div class="space-y-2">
                                @foreach($compra->detalles as $detalle)
                                    <div class="text-gray-700 dark:text-gray-300 pb-1 border-b border-gray-100 dark:border-zinc-700/50 last:border-0 last:pb-0">
                                        {{ $detalle->cantidad }}
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-xs text-center align-middle">
                            <div class="space-y-2">
                                @foreach($compra->detalles as $detalle)
                                    <div class="text-gray-700 dark:text-gray-300 pb-1 border-b border-gray-100 dark:border-zinc-700/50 last:border-0 last:pb-0">
                                        {{ $simboloDivisa ?? 'Bs' }} {{ number_format($detalle->precio_compra, 2) }}
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-center font-semibold text-green-600 align-middle">
                            {{ $simboloDivisa ?? 'Bs' }} {{ number_format($compra->total, 2) }}
                        </td>

                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-sm text-center align-middle">
                            {{ $compra->created_at->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-center align-middle">
                            <span class="px-2 py-1 {{ $compra->estado == 'Completado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-semibold rounded-full">
                                {{ $compra->estado }}
                            </span>
                        </td>

                        <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-center align-middle">
                            <div class="flex justify-center gap-2">
                                
                                <a href="{{ route('admin.compras.show', $compra->id) }}" class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition"><i class="fas fa-eye"></i></a>
                                
                                <a href="{{ route('admin.compras.show', $compra->id) }}?print=true" target="_blank" title="Imprimir Compra" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded transition"><i class="fas fa-print"></i></a>
                                
                                @if($compra->estado != 'Anulado')
                                    <form action="{{ route('admin.compras.destroy', $compra->id) }}" method="post" id="formCompra{{ $compra->id }}">
                                        @csrf @method('DELETE')
                                        <button type="button" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition" onclick="confirmarAnulacionCompra{{ $compra->id }}(event)"><i class="fas fa-ban"></i></button>
                                    </form>
                                    <script>
                                        function confirmarAnulacionCompra{{ $compra->id }}(event) {
                                            event.preventDefault();
                                            Swal.fire({
                                                title: '¿Anular esta compra?',
                                                text: "Esto revertirá el stock de los productos asociados.",
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonText: 'Sí, anular',
                                                confirmButtonColor: '#d33'
                                            }).then((res) => { if(res.isConfirmed) document.getElementById('formCompra{{ $compra->id }}').submit() })
                                        }
                                    </script>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="px-4 py-6 text-center text-gray-500">No hay compras registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        @if(session('success') || session('error'))
            Swal.fire({
                icon: "{{ session('success') ? 'success' : 'error' }}",
                title: "{{ session('success') ? '¡Éxito!' : '¡Error!' }}",
                text: "{{ session('success') ?? session('error') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
</x-layouts::app>