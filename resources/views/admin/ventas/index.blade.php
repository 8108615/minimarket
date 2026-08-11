<x-layouts::app.sidebar title="Listado de Ventas">
    <flux:main>
        <!-- Envolvemos todo en un componente Alpine (x-data) -->
        <div x-data="{
            isOpen: false,
            openTicketModal: false,
            venta: null,
            ticketData: null,

            cargarDetalles(id) {
                fetch(`/admin/ventas/${id}/detalles`)
                    .then(res => res.json())
                    .then(data => {
                        this.venta = data;
                        this.isOpen = true;
                    })
                    .catch(err => {
                        console.error('Error al cargar los detalles:', err);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudieron cargar los detalles.' });
                    });
            },

            cargarTicket(id) {
                fetch(`/admin/ventas/${id}/ticket`)
                    .then(res => res.json())
                    .then(data => {
                        this.ticketData = data;
                        this.openTicketModal = true;
                    })
                    .catch(err => {
                        console.error('Error al cargar el ticket:', err);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo cargar el ticket.' });
                    });
            },

            

            
        }" class="space-y-6">

            <!-- Encabezado y Botón de Nueva Venta -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <flux:heading size="xl" level="1">GESTIÓN DE VENTAS</flux:heading>
                <a href="{{ route('admin.ventas.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition flex items-center gap-2 text-sm shadow-sm">
                    <i class="fas fa-plus"></i> NUEVA VENTA
                </a>
            </div>

            <!-- Barra de Búsqueda y Filtros -->
            <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
                <form method="GET" action="{{ route('admin.ventas.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <input type="text" name="busqueda" value="{{ $busqueda ?? '' }}" placeholder="Buscar por número de comprobante o cliente..." class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm px-3 py-2 text-white">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        @if(!empty($busqueda))
                            <a href="{{ route('admin.ventas.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2">
                                <i class="fas fa-redo"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabla de Ventas -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700 text-sm">
                        <thead class="bg-gray-900 text-white text-center text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Nro</th>
                                <th class="px-4 py-3 text-left">Cliente</th>
                                <th class="px-4 py-3">Atendido</th>
                                <th class="px-4 py-3">Comprobante</th>
                                <th class="px-4 py-3">Fecha de Compra</th>
                                <th class="px-4 py-3">Método de Pago</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Estado</th>
                                <th class="px-4 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-white text-center">
                            @forelse($ventas as $venta)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                                    <!-- Nro con loop iteration y paginación -->
                                    <td class="px-4 py-3 font-medium">
                                        {{ ($ventas->currentPage() - 1) * $ventas->perPage() + $loop->iteration }}
                                    </td>

                                    <!-- Cliente -->
                                    <td class="px-4 py-3 text-left">
                                        {{ $venta->cliente ? $venta->cliente->nombres . ' ' . ($venta->cliente->apellidos ?? '') : 'Cliente General' }}
                                    </td>

                                    <!-- Atendido por (Usuario) -->
                                    <td class="px-4 py-3 text-xs text-gray-400">
                                        {{ $venta->user->name ?? 'N/A' }}
                                    </td>

                                    <!-- Tipo de comprobante y número -->
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 bg-zinc-700 text-xs rounded font-semibold">
                                            {{ $venta->tipo_comprobante }}
                                        </span>
                                        <div class="text-xs text-gray-400 mt-1">{{ $venta->numero_comprobante }}</div>
                                    </td>

                                    <!-- Fecha de Compra -->
                                    <td class="px-4 py-3 text-xs">
                                        {{ $venta->fecha_venta }}
                                    </td>

                                    <!-- Método de Pago -->
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 bg-blue-900/50 text-blue-300 text-xs rounded font-semibold">
                                            {{ $venta->metodo_pago }}
                                        </span>
                                    </td>

                                    <!-- Total -->
                                    <td class="px-4 py-3 font-bold text-emerald-400">
                                        Bs. {{ number_format($venta->total, 2) }}
                                    </td>

                                    <!-- Estado -->
                                    <td class="px-4 py-3">
                                        @if($venta->estado === 'Completado')
                                            <span class="px-2 py-1 bg-emerald-900/50 text-emerald-300 text-xs rounded-full font-semibold">Completado</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-900/50 text-red-300 text-xs rounded-full font-semibold">{{ $venta->estado }}</span>
                                        @endif
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-4 py-3 space-x-1 whitespace-nowrap">

                                        <!-- Ver Detalle en Modal (Botón Ojo) -->
                                        <button type="button" @click="cargarDetalles({{ $venta->id }})" class="inline-flex items-center justify-center p-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded transition" title="Ver Detalle Rápidamente">
                                            <i class="fas fa-eye text-xs"></i>
                                        </button>

                                        <!-- Ver Ticket (Carga por AJAX o mantiene el enlace si prefieres la vista completa) -->
                                        <button type="button" @click="cargarTicket({{ $venta->id }})" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-semibold shadow transition" title="Ver Ticket">
                                            Ver Ticket
                                        </button>

                                        <!-- Anular Venta -->
                                        @if($venta->estado === 'Completado')
                                            <form action="{{ route('admin.ventas.destroy', $venta->id) }}" method="POST" class="inline form-anular">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-red-600 hover:bg-red-700 text-white rounded transition" title="Anular Venta">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-8 text-center text-gray-400 italic">
                                        No se encontraron registros de ventas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="p-4 border-t border-gray-200 dark:border-zinc-700">
                    {{ $ventas->appends(['busqueda' => $busqueda])->links() }}
                </div>
            </div>

            <!-- MODAL DE DETALLES (Alpine) -->
            <div x-show="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" style="display: none;" x-transition>
                <div @click.outside="isOpen = false" class="bg-zinc-900 border border-zinc-700 w-full max-w-3xl rounded-xl shadow-2xl overflow-hidden text-gray-200 relative">

                    <!-- Cabecera del Modal -->
                    <div class="flex justify-between items-center px-6 py-4 border-b border-zinc-700 bg-zinc-800/50">
                        <h3 class="text-base font-bold text-white" x-text="venta ? 'Detalles de Venta: ' + venta.numero_comprobante : 'Cargando...'"></h3>
                        <button @click="isOpen = false" class="text-zinc-400 hover:text-white transition">
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <!-- Cuerpo del Modal -->
                    <template x-if="venta">
                        <div class="p-6 space-y-6">
                            <!-- Datos principales -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-zinc-800/30 p-4 rounded-lg border border-zinc-800">
                                <div>
                                    <span class="font-bold text-gray-400">Cliente:</span>
                                    <span class="text-white" x-text="venta.cliente ? (venta.cliente.nombres + ' ' + (venta.cliente.apellidos || '')) : 'Público General'"></span>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-400">Fecha:</span>
                                    <span class="text-white" x-text="venta.fecha_formateada"></span>
                                </div>
                                <div class="md:col-span-2">
                                    <span class="font-bold text-gray-400">Total:</span>
                                    <span class="text-emerald-400 font-bold text-base" x-text="'Bs ' + Number(venta.total).toFixed(2)"></span>
                                </div>
                            </div>

                            <!-- Datos de Pago -->
                            <div class="bg-zinc-800/60 p-4 rounded-lg border border-zinc-700 text-sm grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="font-bold text-gray-400">Método de Pago:</span>
                                    <span class="text-white uppercase font-semibold" x-text="venta.metodo_pago"></span>
                                </div>
                                <template x-if="venta.metodo_pago === 'Efectivo'">
                                    <div>
                                        <span class="font-bold text-gray-400">Dinero Recibido:</span>
                                        <span class="text-white" x-text="'Bs ' + Number(venta.monto_recibido).toFixed(2)"></span>
                                    </div>
                                </template>
                                <template x-if="venta.metodo_pago === 'Efectivo'">
                                    <div class="md:col-span-2">
                                        <span class="font-bold text-gray-400">Vuelto Entregado:</span>
                                        <span class="text-white" x-text="'Bs ' + Number(venta.vuelto_entregado).toFixed(2)"></span>
                                    </div>
                                </template>
                            </div>

                            <!-- Tabla de Productos -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs">
                                    <thead>
                                        <tr class="border-b border-zinc-700 text-gray-400 uppercase">
                                            <th class="py-2 px-3">Producto</th>
                                            <th class="py-2 px-3 text-center">Cantidad</th>
                                            <th class="py-2 px-3 text-right">Precio</th>
                                            <th class="py-2 px-3 text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-800 text-gray-300">
                                        <template x-for="detalle in venta.detalles" :key="detalle.id">
                                            <tr>
                                                <td class="py-2.5 px-3" x-text="detalle.producto ? detalle.producto.nombre : 'Producto eliminado'"></td>
                                                <td class="py-2.5 px-3 text-center" x-text="detalle.cantidad"></td>
                                                <td class="py-2.5 px-3 text-right" x-text="'Bs ' + Number(detalle.precio_venta).toFixed(2)"></td>
                                                <td class="py-2.5 px-3 text-right font-semibold text-emerald-400" x-text="'Bs ' + Number(detalle.subtotal).toFixed(2)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- MODAL DE TICKET (Alpine) -->
            <div x-show="openTicketModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" style="display: none;" x-transition>
                <div @click.outside="openTicketModal = false" class="bg-white text-black w-full max-w-sm rounded-lg shadow-2xl p-6 relative">
                    
                    <button @click="openTicketModal = false" class="absolute top-2 right-2 text-gray-500 hover:text-black">
                        <i class="fas fa-times"></i>
                    </button>

                    <template x-if="ticketData">
                        <div id="ticket-a-imprimir" class="text-center space-y-2">
                            <!-- Todo tu contenido de tablas y datos va aquí -->
                            <h3 class="font-bold text-lg">TICKET DE VENTA</h3>
                            <p class="text-xs" x-text="'Nro: ' + ticketData.venta.numero_comprobante"></p>
                            <hr>
                            <div class="text-left text-xs my-4 space-y-1">
                                <p>Fecha: <span x-text="ticketData.venta.fecha_venta"></span></p>
                                <p>Cliente: <span x-text="ticketData.venta.cliente ? ticketData.venta.cliente.nombres : 'Público'"></span></p>
                            </div>
                            
                            <table class="w-full text-xs text-left">
                                <thead>
                                    <tr class="border-b"><th>Prod</th><th>Cant</th><th>Total</th></tr>
                                </thead>
                                <tbody>
                                    <template x-for="item in ticketData.detalles" :key="item.id">
                                        <tr>
                                            <td x-text="item.producto"></td>
                                            <td x-text="item.cantidad"></td>
                                            <td x-text="'Bs ' + Number(item.precio_unitario).toFixed(2)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <div class="mt-4 text-right font-bold">
                                Total: <span x-text="'Bs ' + Number(ticketData.venta.total).toFixed(2)"></span>
                            </div>
                        </div>
                    </template>
                    
                    <div class="mt-6 flex gap-3 justify-center">
                        <!-- Botón Imprimir -->
                        <button @click="ejecutarImpresionTicket()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-semibold transition">
                            <i class="fas fa-print mr-1"></i> Imprimir
                        </button>

                        <!-- Botón Cerrar -->
                        <button @click="openTicketModal = false" 
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm font-semibold transition">
                            Cerrar
                        </button>
                    </div>
                </div>

            </div>

        </div>

        <!-- Script de confirmación con SweetAlert para anular -->
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const forms = document.querySelectorAll('.form-anular');
                forms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: "Se anulará la venta y se devolverán los productos al stock.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Sí, anular',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>

        <script>
            function ejecutarImpresionTicket() {
                const contenido = document.getElementById('ticket-a-imprimir').innerHTML;
                const ventanaImpresion = window.open('', '_blank');
                
                ventanaImpresion.document.write('<html><head><title>Ticket de Venta</title>');
                ventanaImpresion.document.write('<style>body{font-family: monospace; padding: 20px; color: #000;} table{width: 100%; border-collapse: collapse;} th, td{text-align: left; padding: 5px; border-bottom: 1px solid #ccc; font-size: 12px;}</style>');
                ventanaImpresion.document.write('</head><body>');
                ventanaImpresion.document.write(contenido);
                ventanaImpresion.document.write('</body></html>');
                
                ventanaImpresion.document.close();
                ventanaImpresion.print();
                ventanaImpresion.close();
            }
        </script>
        @endpush

        {{-- Alertas Globales Integradas en el Layout --}}
        @if ($errors->any())
            <script>
                Swal.fire({
                    icon: 'error',
                    title: '¡Revisa los campos!',
                    text: 'Algunos datos son incorrectos o faltan por completar.',
                    confirmButtonColor: '#3b82f6'
                });
            </script>
        @endif

        @if (($mensaje = Session::get('mensaje')) && ($icono = Session::get('icono')))
            <script>
                Swal.fire({
                    position: "top-end",
                    icon: "{{ $icono }}",
                    title: "{{ $mensaje }}",
                    showConfirmButton: {{ $icono === 'error' ? 'true' : 'false' }},
                    timer: {{ $icono === 'error' ? 'null' : '4000' }}
                });
            </script>
        @endif

        
    </flux:main>
</x-layouts::app.sidebar>
