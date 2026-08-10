<x-layouts::app title="Registrar Nueva Venta">
    <div class="relative mb-6 w-full">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">Registrar Nueva Venta</flux:heading>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selecciona los productos y completa los datos de la transacción.</p>
            </div>
            <a href="{{ route('admin.ventas.index') }}"
                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <!-- Contenedor Principal con Alpine.js -->
    <div x-data="ventaForm()" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- COLUMNA IZQUIERDA: Catálogo / Buscador de Productos -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-zinc-800 p-6 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4"><i class="fas fa-boxes mr-2"></i> Seleccionar Productos</h3>

                <!-- Buscador rápido de productos -->
                <div class="mb-4">
                    <flux:input x-model="search" type="text" icon="magnifying-glass" placeholder="Buscar producto por nombre o código..." class="w-full" />
                </div>

                <!-- Lista de productos disponibles para agregar -->
                <div class="max-h-96 overflow-y-auto space-y-2 pr-2">
                    <template x-for="producto in productosFiltrados" :key="producto.id">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-zinc-900/50 border border-gray-200 dark:border-zinc-700 rounded-lg hover:border-blue-500 transition">
                            <div>
                                <h4 class="font-semibold text-sm text-gray-800 dark:text-gray-200" x-text="producto.nombre"></h4>
                                <div class="text-xs text-gray-500 space-x-2">
                                    <span>Código: <strong x-text="producto.codigo"></strong></span>
                                    <span>Stock: <strong :class="producto.stock > 0 ? 'text-green-600' : 'text-red-600'" x-text="producto.stock"></strong></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-blue-600 dark:text-blue-400 text-sm">$ <span x-text="Number(producto.precio_venta).toFixed(2)"></span></span>
                                <button type="button"
                                    @click="agregarAlCarrito(producto)"
                                    :disabled="producto.stock <= 0"
                                    class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white text-xs font-semibold rounded-lg transition flex items-center gap-1 cursor-pointer">
                                    <i class="fas fa-plus"></i> Agregar
                                </button>
                            </div>
                        </div>
                    </template>
                    <div x-show="productosFiltrados.length === 0" class="text-center py-6 text-gray-500 text-sm">
                        No se encontraron productos disponibles.
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMNA DERECHA: Carrito y Formulario de Venta -->
        <div class="lg:col-span-1">
            <form action="{{ route('admin.ventas.store') }}" method="POST" class="bg-white dark:bg-zinc-800 p-6 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm space-y-4">
                @csrf

                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 border-b pb-2 dark:border-zinc-700"><i class="fas fa-shopping-cart mr-2"></i> Detalle de Venta</h3>

                <!-- Selección de Cliente -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider mb-1">Cliente</label>
                    <select name="cliente_id" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm text-gray-800 dark:text-gray-200 py-2 px-3 focus:ring-blue-500">
                        <option value="">Cliente General (Opcional)</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombres }} {{ $cliente->apellidos ?? '' }} (CI: {{ $cliente->ci ?? 'S/N' }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Método de Pago -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider mb-1">Método de Pago *</label>
                    <select name="metodo_pago" required class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-sm text-gray-800 dark:text-gray-200 py-2 px-3 focus:ring-blue-500">
                        <option value="Efectivo">Efectivo</option>
                        <option value="QR">QR</option>
                        <option value="Tarjeta">Tarjeta</option>
                    </select>
                </div>

                <!-- Lista de Items en el Carrito -->
                <div class="border-t border-b border-gray-200 dark:border-zinc-700 py-3 my-3 space-y-3 max-h-60 overflow-y-auto">
                    <template x-for="(item, index) in carrito" :key="item.id">
                        <div class="bg-gray-50 dark:bg-zinc-900 p-3 rounded-lg border border-gray-200 dark:border-zinc-700 space-y-2">
                            <div class="flex justify-between items-start">
                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200" x-text="item.nombre"></span>
                                <button type="button" @click="eliminarDelCarrito(index)" class="text-red-500 hover:text-red-700 text-xs"><i class="fas fa-trash"></i></button>
                            </div>

                            <div class="flex justify-between items-center text-xs">
                                <span>Precio: $ <span x-text="Number(item.precio_venta).toFixed(2)"></span></span>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="cambiarCantidad(index, -1)" class="px-2 py-0.5 bg-gray-200 dark:bg-zinc-700 rounded">-</button>
                                    <span class="font-bold px-2" x-text="item.cantidad"></span>
                                    <button type="button" @click="cambiarCantidad(index, 1)" class="px-2 py-0.5 bg-gray-200 dark:bg-zinc-700 rounded">+</button>
                                </div>
                            </div>

                            <div class="text-right text-xs font-semibold text-blue-600 dark:text-blue-400">
                                Subtotal: $ <span x-text="(item.cantidad * item.precio_venta).toFixed(2)"></span>
                            </div>

                            <!-- Inputs ocultos para enviar al controlador Laravel -->
                            <input type="hidden" :name="`productos[${index}][id]`" :value="item.id">
                            <input type="hidden" :name="`productos[${index}][cantidad]`" :value="item.cantidad">
                            <input type="hidden" :name="`productos[${index}][precio_venta]`" :value="item.precio_venta">
                        </div>
                    </template>

                    <div x-show="carrito.length === 0" class="text-center py-4 text-gray-400 text-xs">
                        No hay productos agregados a la venta.
                    </div>
                </div>

                <!-- Total de la Venta -->
                <div class="flex justify-between items-center text-lg font-bold text-gray-800 dark:text-gray-200 pt-2">
                    <span>Total a Pagar:</span>
                    <span class="text-blue-600 dark:text-blue-400">$ <span x-text="calcularTotal()"></span></span>
                </div>

                <!-- Botón de Guardar Venta -->
                <button type="submit"
                    :disabled="carrito.length === 0"
                    class="w-full py-2.5 bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 text-white font-semibold rounded-lg transition flex items-center justify-center gap-2 cursor-pointer shadow-md">
                    <i class="fas fa-check-circle"></i> Completar Venta
                </button>
            </form>
        </div>

    </div>

    <!-- Script de Alpine.js para la lógica del carrito -->
    @push('scripts')
    <script>
        function ventaForm() {
            return {
                search: '',
                // Inyectamos los productos desde Laravel asegurando sus tipos de datos
                productosDisponibles: @json($productos),
                carrito: [],

                get productosFiltrados() {
                    if (!this.search) return this.productosDisponibles;
                    return this.productosDisponibles.filter(p =>
                        p.nombre.toLowerCase().includes(this.search.toLowerCase()) ||
                        p.codigo.toLowerCase().includes(this.search.toLowerCase())
                    );
                },

                agregarAlCarrito(producto) {
                    // Verificamos si el producto ya existe en el carrito
                    let index = this.carrito.findIndex(item => item.id === producto.id);

                    if (index !== -1) {
                        // Si ya existe, validamos que no supere el stock máximo antes de aumentar
                        if (this.carrito[index].cantidad < producto.stock) {
                            this.carrito[index].cantidad++;
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Stock limitado',
                                text: 'No hay suficiente stock disponible para agregar más unidades.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    } else {
                        // Si no existe, lo agregamos por primera vez con cantidad 1
                        this.carrito.push({
                            id: producto.id,
                            nombre: producto.nombre,
                            precio_venta: parseFloat(producto.precio_venta),
                            stock: producto.stock,
                            cantidad: 1
                        });
                    }
                },

                cambiarCantidad(index, delta) {
                    let item = this.carrito[index];
                    let nuevaCantidad = item.cantidad + delta;

                    if (nuevaCantidad > 0 && nuevaCantidad <= item.stock) {
                        item.cantidad = nuevaCantidad;
                    } else if (nuevaCantidad > item.stock) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Stock máximo alcanzado',
                            text: 'No puedes vender más unidades de las que hay en stock.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                },

                eliminarDelCarrito(index) {
                    this.carrito.splice(index, 1);
                },

                calcularTotal() {
                    let total = this.carrito.reduce((sum, item) => sum + (item.cantidad * item.precio_venta), 0);
                    return total.toFixed(2);
                }
            }
        }
    </script>
    @endpush
</x-layouts::app>
