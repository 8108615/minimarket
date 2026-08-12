<x-layouts::app.sidebar title="Listado de Ventas">
    <flux:main>
        <div class="space-y-6" x-data="{
            verDetalleModal: false,
            imprimirModal: false,
            ventaSeleccionada: null,
            simboloMoneda: 'Bs.',
            async verDetalle(id) {
                try {
                    let response = await fetch(`/admin/ventas/${id}/detalles`);
                    if (!response.ok) throw new Error('No se pudo obtener el detalle');
                    let data = await response.json();
                    this.ventaSeleccionada = data;
                    this.verDetalleModal = true;
                } catch (e) {
                    alert('Error al cargar los detalles de la venta');
                    console.error(e);
                }
            },
            async prepararImpresion(id) {
                try {
                    let response = await fetch(`/admin/ventas/${id}/ticket`);
                    if (!response.ok) throw new Error('No se pudo obtener el ticket');
                    let data = await response.json();
                    this.ventaSeleccionada = data.venta;
                    this.imprimirModal = true;
                } catch (e) {
                    alert('Error al preparar la impresión');
                    console.error(e);
                }
            }
        }">
            <!-- Encabezado con título y subtítulo -->
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white border-l-4 border-blue-600 pl-3">VENTAS</h2>
                    <p class="text-gray-400 text-sm mt-1 ml-1">Listado de ventas realizadas</p>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Botón Excel -->
                    <a href="{{ route('admin.ventas.excel') }}"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg flex items-center transition shadow-sm text-sm font-semibold gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Excel
                    </a>

                    <!-- Botón PDF -->
                    <a href="{{ route('admin.ventas.pdf') }}" target="_blank"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg flex items-center transition shadow-sm text-sm font-semibold gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2h12a2 2 0 012 2v16a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2zm2 5h8M8 11h8m-8 4h5"></path></svg>
                        PDF
                    </a>

                    <!-- Botón Realizar Venta -->
                    <a href="{{ route('admin.ventas.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition shadow-sm text-sm font-semibold gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Realizar Venta
                    </a>
                </div>
            </div>

            <!-- Buscador con formulario GET -->
            <form method="GET" action="{{ route('admin.ventas.index') }}" class="bg-gray-900 p-4 rounded-t-lg border border-gray-700 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2 flex-wrap">
                    <!-- Búsqueda por texto -->
                    <input type="text"
                        name="busqueda"
                        value="{{ $busqueda ?? '' }}"
                        placeholder="Buscar comprobante o cliente..."
                        class="bg-gray-800 border border-gray-700 text-white rounded px-3 py-1.5 text-sm w-64 focus:outline-none focus:border-blue-500">

                    <!-- Fecha Desde -->
                    <div class="flex items-center gap-1">
                        <span class="text-gray-400 text-xs">Desde:</span>
                        <input type="date" name="fecha_inicio" value="{{ $fechaInicio ?? request('fecha_inicio') }}"
                            class="bg-gray-800 border border-gray-700 text-white rounded px-2 py-1.5 text-xs focus:outline-none focus:border-blue-500">
                    </div>

                    <!-- Fecha Hasta -->
                    <div class="flex items-center gap-1">
                        <span class="text-gray-400 text-xs">Hasta:</span>
                        <input type="date" name="fecha_fin" value="{{ $fechaFin ?? request('fecha_fin') }}"
                            class="bg-gray-800 border border-gray-700 text-white rounded px-2 py-1.5 text-xs focus:outline-none focus:border-blue-500">
                    </div>

                    <!-- Botón Filtrar -->
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-1.5 rounded text-sm transition">
                        Filtrar
                    </button>

                    @if(!empty($busqueda) || !empty($fechaInicio) || !empty($fechaFin))
                        <a href="{{ route('admin.ventas.index') }}"
                                class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-1.5 rounded text-sm transition">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>

            <!-- Tabla de datos -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-x-auto shadow-sm">
                <table class="w-full text-left text-gray-300 text-sm">
                    <thead class="bg-gray-900 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3">NRO</th>
                            <th class="px-4 py-3">COMPROBANTE</th>
                            <th class="px-4 py-3">FECHA</th>
                            <th class="px-4 py-3">CLIENTE</th>
                            <th class="px-4 py-3">USUARIO</th>
                            <th class="px-4 py-3">TIPO</th>
                            <th class="px-4 py-3">PAGO</th>
                            <th class="px-4 py-3">TOTAL</th>
                            <th class="px-4 py-3">ESTADO</th>
                            <th class="px-4 py-3 text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($ventas as $venta)
                            <tr class="hover:bg-gray-700/50 transition">
                                <td class="px-4 py-3 font-medium">{{ ($ventas->currentPage() - 1) * $ventas->perPage() + $loop->iteration }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $venta->numero_comprobante }}</td>
                                <td class="px-4 py-3 text-xs text-gray-400">{{ $venta->fecha_venta ?? $venta->created_at }}</td>
                                <td class="px-4 py-3">{{ $venta->cliente ? $venta->cliente->nombres . ' ' . ($venta->cliente->apellidos ?? '') : 'Público General' }}</td>
                                <td class="px-4 py-3 text-xs text-gray-400">{{ $venta->user->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-zinc-700 text-xs rounded font-semibold">{{ $venta->tipo_comprobante }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-blue-900/50 text-blue-300 text-xs rounded font-semibold">{{ $venta->metodo_pago }}</span>
                                </td>
                                <td class="px-4 py-3 font-bold text-emerald-400">
                                    Bs. {{ number_format($venta->total, 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($venta->estado === 'Completado')
                                        <span class="px-2 py-1 bg-emerald-900/50 text-emerald-300 text-xs rounded-full font-semibold">Completado</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-900/50 text-red-300 text-xs rounded-full font-semibold">{{ $venta->estado }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center space-x-1 whitespace-nowrap">
                                    <!-- Ver Detalle -->
                                    <button @click="verDetalle({{ $venta->id }})"
                                        class="bg-blue-600 hover:bg-blue-500 text-white p-2 rounded transition" title="Ver Detalles">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>

                                    <!-- Ver Ticket / Imprimir -->
                                    <button @click="prepararImpresion({{ $venta->id }})"
                                        class="bg-sky-600 hover:bg-sky-500 text-white p-2 rounded transition" title="Ver Ticket">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </button>

                                    <!-- Eliminar / Anular -->
                                    @if($venta->estado === 'Completado')
                                        <form action="{{ route('admin.ventas.destroy', $venta->id) }}" method="POST" id="miFormulario{{ $venta->id }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="cursor: pointer"
                                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded transition" title="Anular Venta"
                                                onclick="preguntar{{ $venta->id }}(event)">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>

                                        <script>
                                            function preguntar{{ $venta->id }}(event) {
                                                event.preventDefault();

                                                Swal.fire({
                                                    title: '¿Desea anular esta venta?',
                                                    text: 'Se devolverá el stock al inventario.',
                                                    icon: 'question',
                                                    showDenyButton: true,
                                                    confirmButtonText: 'Sí, anular',
                                                    confirmButtonColor: '#a5161d',
                                                    denyButtonColor: '#270a0a',
                                                    denyButtonText: 'Cancelar',
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        document.getElementById('miFormulario{{ $venta->id }}').submit();
                                                    }
                                                });
                                            }
                                        </script>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-8 text-center text-gray-400 italic">
                                    No se encontraron registros de ventas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-4">
                {{ $ventas->appends(['busqueda' => $busqueda])->links() }}
            </div>

            <!-- MODAL DE DETALLES -->
            <div x-show="verDetalleModal" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm flex items-center justify-center z-50 p-4" style="display: none;">
                <div @click.away="verDetalleModal = false" class="bg-gray-800 rounded-xl shadow-2xl w-full max-w-3xl max-h-[85vh] overflow-y-auto border border-gray-700 text-gray-200">
                    <div class="p-4 border-b border-gray-700 flex justify-between items-center bg-gray-900">
                        <h3 class="text-white font-bold text-base">Detalles de Venta: <span x-text="ventaSeleccionada?.numero_comprobante"></span></h3>
                        <button @click="verDetalleModal = false" class="text-gray-400 hover:text-white text-lg font-bold px-2">✕</button>
                    </div>

                    <div class="p-6 space-y-6" x-if="ventaSeleccionada">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-gray-900/50 p-4 rounded-lg border border-gray-700">
                            <div><strong>Cliente:</strong> <span x-text="ventaSeleccionada?.cliente ? (ventaSeleccionada.cliente.nombres + ' ' + (ventaSeleccionada.cliente.apellidos || '')) : 'Público General'"></span></div>
                            <div><strong>Fecha:</strong> <span x-text="ventaSeleccionada?.fecha_formateada"></span></div>
                            <div><strong>Método de Pago:</strong> <span x-text="ventaSeleccionada?.metodo_pago"></span></div>
                            <div><strong>Total:</strong> <span class="text-emerald-400 font-bold" x-text="'Bs. ' + Number(ventaSeleccionada?.total || 0).toFixed(2)"></span></div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-gray-700 text-gray-400 uppercase">
                                        <th class="py-2 px-3">Producto</th>
                                        <th class="py-2 px-3 text-center">Cantidad</th>
                                        <th class="py-2 px-3 text-right">Precio Unitario</th>
                                        <th class="py-2 px-3 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700 text-gray-300">
                                    <template x-for="detalle in (ventaSeleccionada?.detalles || [])" :key="detalle.id">
                                        <tr>
                                            <td class="py-2.5 px-3" x-text="detalle.producto?.nombre || detalle.producto?.name || 'Producto'"></td>
                                            <td class="py-2.5 px-3 text-center" x-text="detalle.cantidad"></td>
                                            <td class="py-2.5 px-3 text-right" x-text="'Bs. ' + Number(detalle.precio_venta).toFixed(2)"></td>
                                            <td class="py-2.5 px-3 text-right font-semibold text-emerald-400" x-text="'Bs. ' + Number(detalle.subtotal).toFixed(2)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL DE TICKET / IMPRESIÓN -->
            <div x-show="imprimirModal" class="fixed inset-0 bg-gray-900/90 backdrop-blur-sm flex flex-col items-center justify-center z-50 p-4 overflow-y-auto" style="display: none;">
                <div class="bg-white p-6 rounded-lg w-full max-w-sm text-black shadow-xl" id="ticket-imprimir">
                    <h3 class="font-bold text-center uppercase text-base underline mb-2" x-text="ventaSeleccionada?.tipo_comprobante"></h3>

                    <div class="text-[11px] space-y-1">
                        <p><strong>Nro:</strong> <span x-text="ventaSeleccionada?.numero_comprobante"></span></p>
                        <p><strong>Fecha:</strong> <span x-text="ventaSeleccionada?.created_at"></span></p>
                        <p><strong>Cliente:</strong> <span x-text="ventaSeleccionada?.cliente ? ventaSeleccionada.cliente.nombres : 'Público General'"></span></p>
                        <p><strong>Atendido por:</strong> <span x-text="ventaSeleccionada?.user?.name || 'N/A'"></span></p>

                        <div class="border-b border-dashed border-gray-400 my-2"></div>

                        <table class="w-full text-[10px]">
                            <template x-for="detalle in (ventaSeleccionada?.detalles || [])">
                                <tr>
                                    <td class="text-left w-3/5" x-text="detalle.producto?.nombre || detalle.producto?.name || 'Prod'"></td>
                                    <td class="text-center" x-text="detalle.cantidad"></td>
                                    <td class="text-right" x-text="Number(detalle.subtotal).toFixed(2)"></td>
                                </tr>
                            </template>
                        </table>

                        <div class="border-b border-dashed border-gray-400 my-2"></div>

                        <p class="text-right font-bold text-base" x-text="'TOTAL: Bs. ' + Number(ventaSeleccionada?.total || 0).toFixed(2)"></p>
                    </div>
                </div>

                <!-- Contenedor con la clase print:hidden para que desaparezca al imprimir -->
                <div class="flex gap-4 mt-6 print:hidden">
                    <button @click="imprimirModal = false"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition shadow-lg text-sm font-semibold">
                        Cerrar
                    </button>
                    <button onclick="window.print()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition shadow-lg text-sm font-semibold">
                        Imprimir
                    </button>
                </div>
            </div>
        </div>


    </flux:main>
</x-layouts::app.sidebar>
