<x-layouts::app.sidebar title="Realizar Nueva Venta">
    <flux:main>
        <div class="space-y-6" x-data="ventaCreate()">

            <!-- Encabezado -->
            <div class="flex justify-between items-center">
                <flux:heading size="xl" level="1">REALIZAR NUEVA VENTA</flux:heading>
                <a href="{{ route('admin.ventas.index') }}"
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition flex items-center gap-2 text-sm">
                    <i class="fas fa-arrow-left"></i> VOLVER
                </a>
            </div>

            <!-- 1. DATOS GENERALES -->
            <div class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden shadow-sm">
                <div class="bg-emerald-600 text-white px-4 py-2 font-bold text-sm tracking-wider">
                    1. DATOS GENERALES
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Cliente -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Cliente</label>
                        <select x-model="cliente_id" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm px-3 py-2 text-white">
                            <option value="">Seleccione un cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombres }} {{ $cliente->apellidos ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Comprobante -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Comprobante</label>
                        <select x-model="tipo_comprobante" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm px-3 py-2 text-white">
                            <option value="Boleta">Boleta (BO-)</option>
                            <option value="Factura">Factura (FA-)</option>
                        </select>
                    </div>

                    <!-- Método de Pago -->
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Pago</label>
                        <select x-model="metodo_pago" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm px-3 py-2 text-white">
                            <option value="Efectivo">EFECTIVO</option>
                            <option value="QR">QR</option>
                            <option value="Tarjeta">TARJETA</option>
                        </select>
                    </div>
                </div>

                <!-- Contenedor inferior para QR (más pequeño) y Código de Transacción -->
                <div class="px-4 pb-4 space-y-4">
                    <!-- Imagen del QR compacta cuando se selecciona QR -->
                    <div class="flex flex-col items-center justify-center bg-zinc-900/40 p-3 rounded-lg border border-zinc-700 w-fit mx-auto" x-show="metodo_pago === 'QR'" style="display: none;">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 text-center">Escanee el código QR para pagar</label>
                        <div class="bg-white p-1.5 rounded shadow border border-zinc-600">
                            <img src="{{ asset('img/QR.jpg') }}" alt="Código QR de Pago" class="w-32 h-32 object-contain rounded">
                        </div>
                    </div>

                    <!-- Código de Transacción si no es efectivo -->
                    <div x-show="metodo_pago !== 'Efectivo'" style="display: none;">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Código de Transacción / Referencia</label>
                        <input type="text" x-model="codigo_transaccion" placeholder="Ingrese número de referencia o transacción" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm px-3 py-2 text-white">
                    </div>
                </div>
            </div>

            <!-- 2. SELECCIÓN DE PRODUCTOS -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden shadow-sm">
                <div class="bg-blue-600 text-white px-4 py-2 font-bold text-sm tracking-wider">
                    2. SELECCIÓN DE PRODUCTOS
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">PRODUCTO</label>
                        <select x-model="productoSeleccionadoId" @change="actualizarDatosProducto()"
                            class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm px-3 py-2 text-white">
                            <option value="">Buscar producto...</option>
                            @foreach ($productos as $prod)
                                <option value="{{ $prod->id }}">
                                    {{ $prod->nombre }} (Stock: {{ $prod->stock }}) - {{ $simboloMoneda }}
                                    {{ number_format($prod->precio_venta, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>



                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <!-- Stock del producto seleccionado -->
                        <div
                            class="bg-gray-50 dark:bg-zinc-900 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-zinc-700 text-sm flex items-center gap-2">
                            <span class="text-gray-400 font-semibold">Stock disponible:</span>
                            <span class="font-bold text-white text-base" x-text="stockActivo">0</span>
                        </div>

                        <!-- Precio del producto seleccionado -->
                        <div
                            class="bg-gray-50 dark:bg-zinc-900 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-zinc-700 text-sm flex items-center gap-2">
                            <span class="text-gray-400 font-semibold">Precio: {{ $simboloMoneda }}</span>
                            <span class="font-bold text-white text-base"
                                x-text="Number(precioActivo).toFixed(2)">0.00</span>
                        </div>

                        <!-- Cantidad a agregar -->
                        <div
                            class="bg-gray-50 dark:bg-zinc-900 px-4 py-2 rounded-lg border border-gray-200 dark:border-zinc-700 flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 uppercase">CANTIDAD</span>
                            <input type="number" min="1" x-model.number="cantidadAgregar"
                                class="w-24 rounded-lg border-gray-300 dark:border-zinc-700 bg-zinc-800 text-sm px-3 py-1.5 text-center font-bold text-white">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="agregarAlCarrito()"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition text-sm flex items-center gap-2 shadow">
                            <i class="fas fa-cart-plus"></i> AGREGAR AL CARRITO
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3. TABLA DEL CARRITO -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse">
                        <thead class="bg-gray-900 text-white text-center text-xs uppercase">
                            <tr>
                                <th class="px-4 py-3">PRODUCTO</th>
                                <th class="px-4 py-3">CANTIDAD</th>
                                <th class="px-4 py-3">PRECIO UNITARIO</th>
                                <th class="px-4 py-3">SUBTOTAL</th>
                                <th class="px-4 py-3">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-sm text-white">
                            <template x-for="(item, index) in carrito" :key="index">
                                <tr class="hover:bg-zinc-700/50 text-center">
                                    <td class="px-4 py-3 text-left font-medium" x-text="item.nombre"></td>
                                    <td class="px-4 py-3">
                                        <input type="number" min="1" :max="item.stock"
                                            x-model.number="item.cantidad" @change="actualizarSubtotal(index)"
                                            class="w-20 text-center rounded border-zinc-700 bg-zinc-900 text-sm py-1 text-white font-bold">
                                    </td>
                                    <td class="px-4 py-3">{{ $simboloMoneda }} <span
                                            x-text="Number(item.precio_venta).toFixed(2)"></span></td>
                                    <td class="px-4 py-3 font-semibold text-emerald-400">{{ $simboloMoneda }} <span
                                            x-text="(item.cantidad * item.precio_venta).toFixed(2)"></span></td>
                                    <td class="px-4 py-3">
                                        <button type="button" @click="eliminarDelCarrito(index)"
                                            class="text-red-500 hover:text-red-400 font-bold p-1">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="carrito.length === 0">
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-400 italic">Ningún
                                        producto agregado al carrito aún.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Totales -->
                <div
                    class="p-4 bg-zinc-900 border-t border-zinc-700 flex flex-col items-end space-y-1 text-sm font-semibold text-white">
                    <div class="text-xl font-bold text-emerald-400">TOTAL A PAGAR: {{ $simboloMoneda }} <span
                            x-text="calcularSubtotalTotal()">0.00</span></div>
                </div>
            </div>

            <!-- 4. PAGO Y FINALIZACIÓN -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-lg border border-gray-200 dark:border-zinc-700 p-4 shadow-sm flex flex-col gap-4">

                <!-- Sección exclusiva si es EFECTIVO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-zinc-900/50 p-4 rounded-lg border border-zinc-700"
                    x-show="metodo_pago === 'Efectivo'">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">MONTO RECIBIDO
                            ({{ $simboloMoneda }})</label>
                        <input type="number" step="0.01" min="0" x-model.number="monto_recibido"
                            placeholder="Ej. 50"
                            class="w-full rounded-lg border-zinc-700 bg-zinc-900 text-base px-3 py-2 font-bold text-emerald-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">CAMBIO / VUELTO
                            ({{ $simboloMoneda }})</label>
                        <div
                            class="w-full rounded-lg border border-zinc-700 bg-zinc-900 text-base px-3 py-2 font-bold text-blue-400">
                            {{ $simboloMoneda }} <span x-text="calcularVuelto()">0.00</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="enviarVenta()" :disabled="carrito.length === 0"
                        class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-600 text-white font-bold rounded-lg transition text-base shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i> FINALIZAR VENTA
                    </button>
                </div>
            </div>

        </div>

        {{-- Alertas Globales del Layout --}}
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

<script>
    function ventaCreate() {
        return {
            listaProductos: {!! json_encode($productos, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!},
            cliente_id: '',
            tipo_comprobante: 'Boleta',
            metodo_pago: 'Efectivo',
            codigo_transaccion: '',
            productoSeleccionadoId: '',
            nombreActivo: '',
            stockActivo: 0,
            precioActivo: 0,
            cantidadAgregar: 1,
            carrito: [],
            monto_recibido: '',

            actualizarDatosProducto() {
                if (!this.productoSeleccionadoId) {
                    this.nombreActivo = '';
                    this.stockActivo = 0;
                    this.precioActivo = 0;
                    return;
                }

                let prod = this.listaProductos.find(p => String(p.id) === String(this.productoSeleccionadoId));
                if (prod) {
                    this.nombreActivo = prod.nombre;
                    this.stockActivo = Number(prod.stock);
                    this.precioActivo = Number(prod.precio_venta);
                    this.cantidadAgregar = 1;
                }
            },

            agregarAlCarrito() {
                if (!this.productoSeleccionadoId) {
                    Swal.fire('Atención', 'Seleccione un producto válido.', 'warning');
                    return;
                }
                if (this.cantidadAgregar <= 0) {
                    Swal.fire('Atención', 'La cantidad debe ser mayor a 0.', 'warning');
                    return;
                }
                if (this.cantidadAgregar > this.stockActivo) {
                    Swal.fire('Stock Insuficiente', `Solo hay ${this.stockActivo} unidades disponibles.`, 'error');
                    return;
                }

                let index = this.carrito.findIndex(i => String(i.id) === String(this.productoSeleccionadoId));
                if (index !== -1) {
                    let nuevaCantidad = this.carrito[index].cantidad + this.cantidadAgregar;
                    if (nuevaCantidad > this.stockActivo) {
                        Swal.fire('Stock Insuficiente', 'La cantidad total en el carrito supera el stock disponible.', 'error');
                        return;
                    }
                    this.carrito[index].cantidad = nuevaCantidad;
                } else {
                    this.carrito.push({
                        id: this.productoSeleccionadoId,
                        nombre: this.nombreActivo,
                        stock: this.stockActivo,
                        precio_venta: this.precioActivo,
                        cantidad: this.cantidadAgregar
                    });
                }

                this.productoSeleccionadoId = '';
                this.nombreActivo = '';
                this.stockActivo = 0;
                this.precioActivo = 0;
                this.cantidadAgregar = 1;
            },

            actualizarSubtotal(index) {
                let item = this.carrito[index];
                if (item.cantidad > item.stock) {
                    Swal.fire('Stock Insuficiente', `Stock máximo disponible: ${item.stock}`, 'warning');
                    item.cantidad = item.stock;
                }
                if (item.cantidad < 1 || isNaN(item.cantidad)) item.cantidad = 1;
            },

            eliminarDelCarrito(index) {
                this.carrito.splice(index, 1);
            },

            calcularSubtotalTotal() {
                return this.carrito.reduce((acc, item) => acc + (item.cantidad * item.precio_venta), 0).toFixed(2);
            },

            calcularVuelto() {
                let total = parseFloat(this.calcularSubtotalTotal());
                let recibido = parseFloat(this.monto_recibido) || 0;
                let vuelto = recibido - total;
                return vuelto > 0 ? vuelto.toFixed(2) : '0.00';
            },

            enviarVenta() {
                let total = parseFloat(this.calcularSubtotalTotal());

                if (this.metodo_pago === 'Efectivo') {
                    let recibido = parseFloat(this.monto_recibido) || 0;
                    if (recibido < total) {
                        Swal.fire('Monto Insuficiente', 'El dinero recibido es menor al total a pagar.', 'error');
                        return;
                    }
                }

                Swal.fire({
                    title: '¿Confirmar venta?',
                    text: "Se procesará la transacción y se descontará el stock de los productos.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, finalizar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#10b981'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('admin.ventas.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                cliente_id: this.cliente_id || null,
                                tipo_comprobante: this.tipo_comprobante,
                                metodo_pago: this.metodo_pago,
                                codigo_transaccion: this.codigo_transaccion,
                                monto_recibido: this.metodo_pago === 'Efectivo' ? this.monto_recibido : total,
                                productos: this.carrito.map(i => ({
                                    id: i.id,
                                    cantidad: i.cantidad,
                                    precio_venta: i.precio_venta
                                }))
                            })
                        })
                        .then(async res => {
                            let data = await res.json();
                            if (!res.ok) throw new Error(data.message || 'Error al procesar la venta');
                            return data;
                        })
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Venta exitosa!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ route('admin.ventas.index') }}";
                            });
                        })
                        .catch(error => {
                            Swal.fire('Error', error.message, 'error');
                        });
                    }
                });
            }
        }
    }
</script>


