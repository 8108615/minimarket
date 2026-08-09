<x-layouts::app title="Registrar Compra">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Registrar Nueva Compra</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-sm">
        <form action="{{ route('admin.compras.store') }}" method="POST" id="form-compra">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="proveedor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Proveedor *</label>
                    <select name="proveedor_id" id="proveedor_id" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 text-sm p-2.5 shadow-sm" required>
                        <option value="">Seleccione un proveedor</option>
                        @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                {{ $proveedor->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="fecha_compra" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha de Compra *</label>
                    <input type="date" name="fecha_compra" id="fecha_compra" value="{{ old('fecha_compra', date('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 text-sm p-2.5 shadow-sm" required>
                </div>

                <div class="flex items-end">
                    <button type="button" onclick="abrirModalNuevoProducto()" class="w-full px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-plus-circle"></i> Nuevo Producto al Vuelo
                    </button>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Productos a Comprar</h3>
                    <button type="button" onclick="agregarFila()" class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-lg transition flex items-center gap-1">
                        <i class="fas fa-plus"></i> Agregar Fila
                    </button>
                </div>

                <div class="overflow-x-visible rounded-lg border border-gray-200 dark:border-zinc-700 min-h-[300px]">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700" id="tabla-detalles">
                        <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                            <tr>
                                <th class="px-3 py-2 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">Producto (Buscador)</th>
                                <th class="px-3 py-2 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase w-32">Cantidad</th>
                                <th class="px-3 py-2 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase w-36">Costo Unit.</th>
                                <th class="px-3 py-2 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase w-36">Subtotal</th>
                                <th class="px-3 py-2 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase w-16">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800" id="detalles-body">
                            </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end mb-6">
                <div class="bg-gray-50 dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 w-64 text-right">
                    <span class="text-sm text-gray-500 dark:text-gray-400 block">Total a Pagar:</span>
                    <span id="lbl-total" class="text-2xl font-bold text-green-600">Bs 0.00</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-zinc-700">
                <a href="{{ route('admin.compras.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition text-sm">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-save"></i> Guardar Compra
                </button>
            </div>
        </form>
    </div>

    <script>
        const productosDisponibles = @json($productos);
        let filaIdx = 0;

        function agregarFila(productoPreselected = null, costoPreselected = 0) {
            const tbody = document.getElementById('detalles-body');
            const tr = document.createElement('tr');
            tr.className = "hover:bg-gray-50/50 dark:hover:bg-zinc-700/30 transition relative";
            tr.id = `fila-${filaIdx}`;

            let nombreSeleccionado = "Seleccionar o buscar producto...";
            let idSeleccionado = "";
            let costoSeleccionado = costoPreselected;

            if (productoPreselected) {
                const prod = productosDisponibles.find(p => p.id == productoPreselected);
                if (prod) {
                    nombreSeleccionado = `${prod.nombre} — [${prod.codigo}]`;
                    idSeleccionado = prod.id;
                    costoSeleccionado = prod.precio_compra;
                }
            }

            tr.innerHTML = `
                <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 relative">
                    <input type="hidden" name="productos[${filaIdx}][producto_id]" class="producto-id-input" value="${idSeleccionado}" required>
                    
                    <div class="relative">
                        <div onclick="toggleDropdown(${filaIdx})" class="w-full bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 hover:border-blue-500 dark:hover:border-blue-500 rounded-lg px-3 py-2 text-sm cursor-pointer flex justify-between items-center select-display text-gray-800 dark:text-gray-200 shadow-sm transition">
                            <span class="truncate selected-text font-medium">${nombreSeleccionado}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400 ml-2"></i>
                        </div>

                        <div id="dropdown-${filaIdx}" class="hidden absolute left-0 right-0 mt-2 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-xl shadow-2xl z-50 p-3">
                            <div class="relative mb-2">
                                <i class="fas fa-search absolute left-3 top-3 text-xs text-gray-400"></i>
                                <input type="text" placeholder="Escribe para buscar por nombre o código..." oninput="filtrarProductos(this, ${filaIdx})" class="w-full pl-9 pr-3 py-2 text-sm bg-gray-50 dark:bg-zinc-800/80 border border-gray-200 dark:border-zinc-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-100 search-input">
                            </div>
                            <div class="max-h-56 overflow-y-auto space-y-1 options-list divide-y divide-gray-100 dark:divide-zinc-800/50">
                                ${generarOpcionesHtml()}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700">
                    <input type="number" name="productos[${filaIdx}][cantidad]" value="1" min="1" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 text-sm p-2 text-center cantidad-input bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 shadow-sm" required oninput="calcularTotales()">
                </td>
                <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700">
                    <input type="number" step="0.01" min="0" name="productos[${filaIdx}][precio_compra]" value="${costoSeleccionado}" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 text-sm p-2 text-center costo-input bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 shadow-sm" required oninput="calcularTotales()">
                </td>
                <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-center font-bold text-gray-700 dark:text-gray-200 subtotal-text">
                    0.00
                </td>
                <td class="px-3 py-3 border border-gray-200 dark:border-zinc-700 text-center">
                    <button type="button" onclick="eliminarFila(${filaIdx})" class="p-2 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition shadow-sm"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;

            tbody.appendChild(tr);
            filaIdx++;
            calcularTotales();
        }

        function generarOpcionesHtml() {
            let html = '';
            productosDisponibles.forEach(p => {
                html += `<div onclick="seleccionarProducto(this, '${p.id}', '${p.nombre} — [${p.codigo}]', ${p.precio_compra})" class="p-2.5 text-sm hover:bg-blue-50 dark:hover:bg-zinc-800 cursor-pointer rounded-lg transition flex justify-between items-center text-gray-700 dark:text-gray-300">
                    <div>
                        <div class="font-semibold text-gray-900 dark:text-gray-100">${p.nombre}</div>
                        <div class="text-xs text-gray-400">Código: ${p.codigo}</div>
                    </div>
                    <span class="text-xs bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-gray-400 px-2 py-1 rounded font-mono">Stock: ${p.stock}</span>
                </div>`;
            });
            return html;
        }

        function toggleDropdown(idx) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if(el.id !== `dropdown-${idx}`) el.classList.add('hidden');
            });

            const dropdown = document.getElementById(`dropdown-${idx}`);
            dropdown.classList.toggle('hidden');
            if(!dropdown.classList.contains('hidden')) {
                const searchInput = dropdown.querySelector('.search-input');
                searchInput.value = '';
                filtrarProductos(searchInput, idx);
                searchInput.focus();
            }
        }

        function filtrarProductos(input, idx) {
            const filtro = input.value.toLowerCase();
            const list = document.getElementById(`dropdown-${idx}`).querySelector('.options-list');
            let html = '';
            
            const filtrados = productosDisponibles.filter(p => 
                p.nombre.toLowerCase().includes(filtro) || p.codigo.toLowerCase().includes(filtro)
            );

            if(filtrados.length === 0) {
                html = `<div class="p-4 text-sm text-gray-400 text-center">No se encontraron productos coincidentes</div>`;
            } else {
                filtrados.forEach(p => {
                    html += `<div onclick="seleccionarProducto(this, '${p.id}', '${p.nombre} — [${p.codigo}]', ${p.precio_compra})" class="p-2.5 text-sm hover:bg-blue-50 dark:hover:bg-zinc-800 cursor-pointer rounded-lg transition flex justify-between items-center text-gray-700 dark:text-gray-300">
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-gray-100">${p.nombre}</div>
                            <div class="text-xs text-gray-400">Código: ${p.codigo}</div>
                        </div>
                        <span class="text-xs bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-gray-400 px-2 py-1 rounded font-mono">Stock: ${p.stock}</span>
                    </div>`;
                });
            }
            list.innerHTML = html;
        }

        function seleccionarProducto(element, id, nombreTexto, costo) {
            const tr = element.closest('tr');
            tr.querySelector('.producto-id-input').value = id;
            tr.querySelector('.selected-text').textContent = nombreTexto;
            tr.querySelector('.costo-input').value = costo;
            
            element.closest('[id^="dropdown-"]').classList.add('hidden');
            calcularTotales();
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('td')) {
                document.querySelectorAll('[id^="dropdown-"]').forEach(el => el.classList.add('hidden'));
            }
        });

        function eliminarFila(idx) {
            const tr = document.getElementById(`fila-${idx}`);
            tr.remove();
            calcularTotales();
        }

        function calcularTotales() {
            let totalGeneral = 0;
            document.querySelectorAll('#detalles-body tr').forEach(tr => {
                const cantidad = parseFloat(tr.querySelector('.cantidad-input').value) || 0;
                const costo = parseFloat(tr.querySelector('.costo-input').value) || 0;
                const subtotal = cantidad * costo;
                tr.querySelector('.subtotal-text').textContent = subtotal.toFixed(2);
                totalGeneral += subtotal;
            });

            document.getElementById('lbl-total').textContent = `Bs ` + totalGeneral.toFixed(2);
        }

        // Modal para crear producto al vuelo adaptado al modo oscuro/claro
        function abrirModalNuevoProducto() {
            Swal.fire({
                title: '<span class="text-gray-900 dark:text-gray-100 font-bold text-xl">Registrar Producto Rápido</span>',
                html: `
                    <div class="text-left space-y-3 text-sm">
                        <div>
                            <label class="block font-medium mb-1 text-gray-700 dark:text-gray-300">Nombre *</label>
                            <input id="swal-nombre" class="w-full p-2.5 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Nombre del producto">
                        </div>
                        <div>
                            <label class="block font-medium mb-1 text-gray-700 dark:text-gray-300">Código de Barra *</label>
                            <input id="swal-codigo" class="w-full p-2.5 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Código o barra">
                        </div>
                        <div>
                            <label class="block font-medium mb-1 text-gray-700 dark:text-gray-300">Categoría *</label>
                            <select id="swal-categoria" class="w-full p-2.5 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-medium mb-1 text-gray-700 dark:text-gray-300">Costo *</label>
                                <input id="swal-costo" type="number" step="0.01" class="w-full p-2.5 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block font-medium mb-1 text-gray-700 dark:text-gray-300">Precio Venta *</label>
                                <input id="swal-venta" type="number" step="0.01" class="w-full p-2.5 bg-white dark:bg-zinc-900 border border-gray-300 dark:border-zinc-700 rounded-lg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                `,
                background: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff',
                confirmButtonText: 'Guardar y Seleccionar',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-2xl border border-gray-200 dark:border-zinc-700 shadow-2xl p-6',
                    confirmButton: 'px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition text-sm mr-2',
                    cancelButton: 'px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition text-sm'
                },
                preConfirm: () => {
                    const nombre = document.getElementById('swal-nombre').value;
                    const codigo = document.getElementById('swal-codigo').value;
                    const categoria_id = document.getElementById('swal-categoria').value;
                    const precio_compra = document.getElementById('swal-costo').value;
                    const precio_venta = document.getElementById('swal-venta').value;
                    
                    if(!nombre || !categoria_id || !precio_compra || !precio_venta) {
                        Swal.showValidationMessage('Complete los campos obligatorios');
                    }
                    return { nombre, codigo, categoria_id, precio_compra, precio_venta };
                }
            }).then((result) => {
                if(result.isConfirmed) {
                    fetch("{{ route('admin.compras.producto-ajax') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            productosDisponibles.push(data.producto);
                            agregarFila(data.producto.id);
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                text: 'Producto creado y agregado a la compra.',
                                background: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#f4f4f5' : '#1f2937',
                                confirmButtonColor: '#4f46e5'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'No se pudo crear el producto',
                                background: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff',
                                color: document.documentElement.classList.contains('dark') ? '#f4f4f5' : '#1f2937',
                                confirmButtonColor: '#4f46e5'
                            });
                        }
                    }).catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error inesperado',
                            background: document.documentElement.classList.contains('dark') ? '#18181b' : '#ffffff',
                            color: document.documentElement.classList.contains('dark') ? '#f4f4f5' : '#1f2937',
                            confirmButtonColor: '#4f46e5'
                        });
                    });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            agregarFila();
        });
    </script>
</x-layouts::app>