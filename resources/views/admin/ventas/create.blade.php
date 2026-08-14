<x-layouts::app.sidebar title="Realizar Nueva Venta">
    <flux:main>
        <div class="space-y-6" x-data="ventaCreate()">

            <!-- Encabezado -->
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white border-l-4 border-blue-600 pl-3">REALIZAR NUEVA VENTA</h2>
                    <p class="text-gray-400 text-sm mt-1 ml-1">Complete los datos para registrar una nueva venta</p>
                </div>
                <a href="{{ route('admin.ventas.index') }}"
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-semibold rounded-lg transition flex items-center gap-2 text-sm shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    VOLVER
                </a>
            </div>

            <!-- 1. DATOS GENERALES -->
            <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden shadow-sm">
                <div class="bg-emerald-600 text-white px-4 py-2.5 font-bold text-sm tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    1. DATOS GENERALES
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Cliente -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Cliente</label>
                        <select x-model="cliente_id" class="w-full rounded-lg border-gray-700 bg-gray-900 text-sm px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                            <option value="">Seleccione un cliente (Opcional)</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nombres }} {{ $cliente->apellidos ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Comprobante -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Comprobante</label>
                        <select x-model="tipo_comprobante" class="w-full rounded-lg border-gray-700 bg-gray-900 text-sm px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                            <option value="Boleta">Boleta (BO-)</option>
                            <option value="Factura">Factura (FA-)</option>
                        </select>
                    </div>

                    <!-- Método de Pago -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Pago</label>
                        <select x-model="metodo_pago" class="w-full rounded-lg border-gray-700 bg-gray-900 text-sm px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                            <option value="Efectivo">EFECTIVO</option>
                            <option value="QR">QR</option>
                            <option value="Tarjeta">TARJETA</option>
                        </select>
                    </div>
                </div>

                <!-- Contenedor inferior para QR y Código de Transacción -->
                <div class="px-4 pb-4 space-y-4">
                    <!-- Imagen del QR compacta cuando se selecciona QR -->
                    <div class="flex flex-col items-center justify-center bg-gray-900/60 p-3 rounded-lg border border-gray-700 w-fit mx-auto" x-show="metodo_pago === 'QR'" style="display: none;">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2 text-center">Escanee el código QR para pagar</label>
                        <div class="bg-white p-1.5 rounded shadow border border-gray-600">
                            <img src="{{ asset('img/QR.jpg') }}" alt="Código QR de Pago" class="w-32 h-32 object-contain rounded">
                        </div>
                    </div>

                    <!-- Código de Transacción si no es efectivo -->
                    <div x-show="metodo_pago !== 'Efectivo'" style="display: none;">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Código de Transacción / Referencia</label>
                        <input type="text" x-model="codigo_transaccion" placeholder="Ingrese número de referencia o transacción" class="w-full rounded-lg border-gray-700 bg-gray-900 text-sm px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- 2. SELECCIÓN DE PRODUCTOS -->
            <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden shadow-sm">
                <div class="bg-blue-600 text-white px-4 py-2.5 font-bold text-sm tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    2. SELECCIÓN DE PRODUCTOS
                </div>
                <div class="p-4 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">PRODUCTO</label>
                        <select x-model="productoSeleccionadoId" @change="actualizarDatosProducto()"
                            class="w-full rounded-lg border-gray-700 bg-gray-900 text-sm px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                            <option value="">Buscar producto...</option>
                            @foreach ($productos as $prod)
                                <option value="{{ $prod->id }}">
                                    {{ $prod->nombre }} (Stock: {{ $prod->stock }}) - {{ $simboloMoneda }} {{ number_format($prod->precio_venta, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <!-- Stock del producto seleccionado -->
                        <div class="bg-gray-900 px-4 py-2.5 rounded-lg border border-gray-700 text-sm flex items-center gap-2">
                            <span class="text-gray-400 font-semibold">Stock disponible:</span>
                            <span class="font-bold text-white text-base" x-text="stockActivo">0</span>
                        </div>

                        <!-- Precio del producto seleccionado -->
                        <div class="bg-gray-900 px-4 py-2.5 rounded-lg border border-gray-700 text-sm flex items-center gap-2">
                            <span class="text-gray-400 font-semibold">Precio: {{ $simboloMoneda }}</span>
                            <span class="font-bold text-emerald-400 text-base" x-text="Number(precioActivo).toFixed(2)">0.00</span>
                        </div>

                        <!-- Cantidad a agregar -->
                        <div class="bg-gray-900 px-4 py-2 rounded-lg border border-gray-700 flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 uppercase">CANTIDAD</span>
                            <input type="number" min="1" x-model.number="cantidadAgregar"
                                class="w-24 rounded-lg border-gray-700 bg-gray-800 text-sm px-3 py-1.5 text-center font-bold text-white focus:outline-none focus:border-blue-500">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" @click="agregarAlCarrito()"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition text-sm flex items-center gap-2 shadow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            AGREGAR AL CARRITO
                        </button>
                    </div>
                </div>
            </div>

            <!-- 3. TABLA DEL CARRITO -->
            <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-gray-900 uppercase text-xs text-center text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-left">PRODUCTO</th>
                                <th class="px-4 py-3">CANTIDAD</th>
                                <th class="px-4 py-3">PRECIO UNITARIO</th>
                                <th class="px-4 py-3">SUBTOTAL</th>
                                <th class="px-4 py-3">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700 text-white">
                            <template x-for="(item, index) in carrito" :key="index">
                                <tr class="hover:bg-gray-700/50 text-center transition">
                                    <td class="px-4 py-3 text-left font-medium text-gray-200" x-text="item.nombre"></td>
                                    <td class="px-4 py-3">
                                        <input type="number" min="1" :max="item.stock"
                                            x-model.number="item.cantidad" @change="actualizarSubtotal(index)"
                                            class="w-20 text-center rounded border-gray-700 bg-gray-900 text-sm py-1 text-white font-bold focus:outline-none focus:border-blue-500">
                                    </td>
                                    <td class="px-4 py-3">{{ $simboloMoneda }} <span x-text="Number(item.precio_venta).toFixed(2)"></span></td>
                                    <td class="px-4 py-3 font-semibold text-emerald-400">{{ $simboloMoneda }} <span x-text="(item.cantidad * item.precio_venta).toFixed(2)"></span></td>
                                    <td class="px-4 py-3">
                                        <button type="button" @click="eliminarDelCarrito(index)"
                                            class="text-red-400 hover:text-red-300 font-bold p-1 transition" title="Eliminar">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="carrito.length === 0">
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-400 italic">
                                        Ningún producto agregado al carrito aún.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Totales -->
                <div class="p-4 bg-gray-900 border-t border-gray-700 flex flex-col items-end space-y-1 text-sm font-semibold text-white">
                    <div class="text-xl font-bold text-emerald-400">TOTAL A PAGAR: {{ $simboloMoneda }} <span x-text="calcularSubtotalTotal()">0.00</span></div>
                </div>
            </div>

            <!-- 4. PAGO Y FINALIZACIÓN -->
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-4 shadow-sm flex flex-col gap-4">
                <!-- Sección exclusiva si es EFECTIVO -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-900/60 p-4 rounded-lg border border-gray-700"
                    x-show="metodo_pago === 'Efectivo'">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">MONTO RECIBIDO ({{ $simboloMoneda }})</label>
                        <input type="number" step="0.01" min="0" x-model.number="monto_recibido"
                            placeholder="Ej. 50"
                            class="w-full rounded-lg border-gray-700 bg-gray-900 text-base px-3 py-2 font-bold text-emerald-400 focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">CAMBIO / VUELTO ({{ $simboloMoneda }})</label>
                        <div class="w-full rounded-lg border border-gray-700 bg-gray-900 text-base px-3 py-2 font-bold text-blue-400">
                            {{ $simboloMoneda }} <span x-text="calcularVuelto()">0.00</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="enviarVenta()" :disabled="carrito.length === 0"
                        class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-600 text-white font-bold rounded-lg transition text-base shadow-lg flex items-center justify-center gap-2 cursor-pointer disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        FINALIZAR VENTA
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