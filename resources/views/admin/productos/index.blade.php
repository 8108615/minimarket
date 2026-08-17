<x-layouts::app title="Listado de Productos">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Gestión de Productos</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ route('admin.productos.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre o código..."
                        value="{{ request('buscar') }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ route('admin.productos.index') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="flex-1 justify-end flex gap-2">
            @can('Imprimir codigos de barras')
            <a href="{{ route('admin.productos.barras') }}" target="_blank"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-barcode"></i> Códigos de Barra
            </a>
            @endcan

            @can('Exportar productos excel')
            <a href="{{ route('admin.productos.excel') }}"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Excel
            </a>
            @endcan

            @can('Exportar productos pdf')
            <a href="{{ route('admin.productos.pdf') }}"
                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
            @endcan

            @can('Ver formulario de creacion de producto')
            <a href="{{ route('admin.productos.create') }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-2"></i> Crear nuevo
            </a>
            @endcan
        </div>
    </div>

    @if(isset($productosCriticos) && $productosCriticos->count() > 0)
        <div class="mt-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                <div>
                    <h3 class="text-sm font-bold text-red-800">¡Alerta de inventario bajo!</h3>
                    <p class="text-sm text-red-700">
                        Tienes <b>{{ $productosCriticos->count() }}</b> producto(s) en estado crítico.
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (request('buscar'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-xl text-gray-700 dark:text-gray-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $productos->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $productos->total() }}</span>
                {{ $productos->total() == 1 ? 'resultado' : 'resultados' }}
                con la búsqueda: <span class="font-semibold">"{{ request('buscar') }}"</span>
            </p>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nro</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Imagen</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Código</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Producto</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Categoría</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @forelse ($productos as $producto)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $loop->iteration }}</td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-center">
                            @if ($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-10 h-10 object-cover rounded-full mx-auto">
                            @else
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 dark:bg-zinc-700 text-gray-500 text-xs"><i class="fas fa-box"></i></span>
                            @endif
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center font-mono">{{ $producto->codigo }}</td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center font-semibold">{{ $producto->nombre }}</td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center font-semibold text-green-600">{{ $simboloDivisa }} {{ number_format($producto->precio_venta, 2) }}</td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $producto->stock <= $producto->stock_minimo ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $producto->stock }}
                            </span>
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-center">
                            <span class="px-2 py-1 {{ $producto->estado == 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-semibold rounded-full">{{ $producto->estado }}</span>
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-center">
                            <div class="flex justify-center gap-2">
                                @can('Ver datos del producto')
                                <a href="{{ route('admin.productos.show', $producto->id) }}" class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition"><i class="fas fa-eye"></i></a>
                                @endcan

                                @can('Ajustar stock de producto')
                                <button type="button" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded transition" onclick="abrirModalStock({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->stock }})"><i class="fas fa-boxes"></i></button>
                                @endcan

                                @can('Ver formulario de edicion de producto')
                                <a href="{{ route('admin.productos.edit', $producto->id) }}" class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition"><i class="fas fa-pencil-alt"></i></a>
                                @endcan

                                @can('Eliminar producto')
                                <form action="{{ url('/admin/producto/' . $producto->id) }}" method="post" id="formProducto{{ $producto->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition" onclick="confirmarEliminacionProducto{{ $producto->id }}(event)"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                <script>
                                    function confirmarEliminacionProducto{{ $producto->id }}(event) {
                                        event.preventDefault();
                                        Swal.fire({
                                            title: '¿Eliminar {{ $producto->nombre }}?',
                                            icon: 'question',
                                            showCancelButton: true,
                                            confirmButtonText: 'Eliminar',
                                            confirmButtonColor: '#d33'
                                        }).then((res) => { if(res.isConfirmed) document.getElementById('formProducto{{ $producto->id }}').submit() })
                                    }
                                </script>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-4 py-6 text-center text-gray-500">No hay productos.</td></tr>
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

        function abrirModalStock(idProducto, nombreProducto, stockActual) {
            Swal.fire({
                title: 'Ajustar Stock: ' + nombreProducto,
                html: `
                    <div class="text-left mb-2 text-sm">Stock actual: <b>${stockActual} unidades</b></div>
                    <select id="swal-tipo" class="w-full p-2 border rounded mb-3">
                        <option value="entrada">➕ Registrar Entrada</option>
                        <option value="salida">➖ Registrar Salida</option>
                    </select>
                    <input id="swal-cantidad" type="number" min="1" value="1" class="w-full p-2 border rounded">
                `,
                confirmButtonText: 'Guardar Ajuste',
                showCancelButton: true,
                preConfirm: () => {
                    const tipo = document.getElementById('swal-tipo').value;
                    const cantidad = document.getElementById('swal-cantidad').value;
                    if (cantidad <= 0) Swal.showValidationMessage('Ingrese cantidad mayor a 0');
                    return { tipo, cantidad };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ url('admin/productos') }}/" + idProducto + "/ajustar-stock";
                    const inputs = [
                        {name: '_token', value: '{{ csrf_token() }}'},
                        {name: 'tipo_movimiento', value: result.value.tipo},
                        {name: 'cantidad', value: result.value.cantidad}
                    ];
                    inputs.forEach(i => {
                        const el = document.createElement('input');
                        el.type = 'hidden'; el.name = i.name; el.value = i.value;
                        form.appendChild(el);
                    });
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</x-layouts::app>
