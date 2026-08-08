<x-layouts::app title="Editar Producto">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Editar Producto: {{ $producto->nombre }}</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="max-w-4xl bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-lg p-6 shadow-sm">
        <form action="{{ route('admin.productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del Producto *</label>
                    <flux:input name="nombre" id="nombre" type="text" icon="cube" placeholder="Ej. Coca Cola 2L" value="{{ old('nombre', $producto->nombre) }}" required />
                    @error('nombre')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Código de Barra -->
                <div>
                    <label for="codigo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Código de Barra / Interno *</label>
                    <flux:input name="codigo" id="codigo" type="text" icon="qr-code" placeholder="Ej. 777123456789" value="{{ old('codigo', $producto->codigo) }}" required />

                    <!-- Vista previa del código de barras -->
                    <div class="mt-2 p-2 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-lg flex flex-col items-center justify-center">
                        <span class="text-xs text-gray-500 dark:text-gray-400 mb-1">Vista previa del Código de Barra</span>
                        <svg id="barcode-preview"></svg>
                    </div>

                    @error('codigo')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría *</label>
                    <select name="categoria_id" id="categoria_id" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5 shadow-sm" required>
                        <option value="">Seleccione una categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado *</label>
                    <select name="estado" id="estado" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5 shadow-sm" required>
                        <option value="Activo" {{ old('estado', $producto->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                        <option value="Inactivo" {{ old('estado', $producto->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Precio Compra -->
                <div>
                    <label for="precio_compra" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Precio de Compra (<span class="text-blue-600 dark:text-blue-400 font-bold">{{ $simboloDivisa }}</span>) *
                    </label>
                    <flux:input name="precio_compra" id="precio_compra" type="number" step="0.01" min="0" icon="currency-dollar" placeholder="0.00" value="{{ old('precio_compra', $producto->precio_compra) }}" required />
                    @error('precio_compra')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Precio Venta -->
                <div>
                    <label for="precio_venta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Precio de Venta (<span class="text-blue-600 dark:text-blue-400 font-bold">{{ $simboloDivisa }}</span>) *
                    </label>
                    <flux:input name="precio_venta" id="precio_venta" type="number" step="0.01" min="0" icon="currency-dollar" placeholder="0.00" value="{{ old('precio_venta', $producto->precio_venta) }}" required />
                    @error('precio_venta')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Stock Inicial -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock Actual *</label>
                    <flux:input name="stock" id="stock" type="number" min="0" icon="circle-stack" placeholder="0" value="{{ old('stock', $producto->stock) }}" required />
                    @error('stock')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Stock Mínimo -->
                <div>
                    <label for="stock_minimo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stock Mínimo (Alerta) *</label>
                    <flux:input name="stock_minimo" id="stock_minimo" type="number" min="0" icon="exclamation-triangle" placeholder="5" value="{{ old('stock_minimo', $producto->stock_minimo) }}" required />
                    @error('stock_minimo')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Imagen del Producto con Estilo de Usuarios (Muestra la actual si existe) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Imagen del Producto</label>
                    <div class="flex items-center gap-6 mt-2">
                        <div class="h-20 w-20 flex-shrink-0 relative">
                            <div class="h-full w-full rounded-2xl border-2 border-slate-100 overflow-hidden bg-slate-50 dark:bg-zinc-900 flex items-center justify-center shadow-inner">
                                <img id="image-preview"
                                     src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : '#' }}"
                                     alt="Preview"
                                     class="{{ $producto->imagen ? '' : 'hidden' }} h-full w-full object-cover">
                                <flux:icon id="placeholder-icon" name="photo" class="{{ $producto->imagen ? 'hidden' : '' }} text-slate-300 h-8 w-8" />
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            <input type="file" name="imagen" id="foto-input" class="hidden" accept="image/*">
                            <label for="foto-input" class="cursor-pointer flex items-center gap-2 px-6 py-3 bg-white dark:bg-zinc-900 border-2 border-slate-200 dark:border-zinc-700 rounded-2xl text-slate-600 dark:text-slate-300 font-bold hover:border-blue-500 transition-all">
                                <flux:icon name="cloud-arrow-up" variant="micro" />
                                <span>Cambiar Imagen</span>
                            </label>
                            <span id="file-chosen" class="text-sm text-slate-400 italic">
                                {{ $producto->imagen ? basename($producto->imagen) : 'Ningún archivo seleccionado' }}
                            </span>
                        </div>
                    </div>
                    @error('imagen')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="w-full rounded-lg border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 text-sm p-2.5 shadow-sm" placeholder="Detalles o características del producto...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    @error('descripcion')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-zinc-700">
                <a href="{{ route('admin.productos.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition text-sm">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition text-sm flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-save"></i> Actualizar Producto
                </button>
            </div>
        </form>
    </div>

    <!-- Script de JsBarcode y Vista Previa de Imagen -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Lógica del Código de Barra ---
            const codigoInput = document.getElementById('codigo');

            function generarBarcode(valor) {
                if (valor.trim() !== '') {
                    try {
                        JsBarcode("#barcode-preview", valor, {
                            format: "CODE128",
                            lineColor: "#000",
                            width: 1.8,
                            height: 50,
                            displayValue: true
                        });
                    } catch (e) {
                        console.error(e);
                    }
                } else {
                    document.getElementById('barcode-preview').innerHTML = "";
                }
            }

            if (codigoInput.value) {
                generarBarcode(codigoInput.value);
            }

            codigoInput.addEventListener('input', function() {
                generarBarcode(this.value);
            });


            // --- Lógica de Vista Previa de Imagen (Estilo Usuarios) ---
            const actualBtn = document.getElementById('foto-input');
            if (actualBtn) {
                const fileChosen = document.getElementById('file-chosen');
                const preview = document.getElementById('image-preview');
                const placeholderIcon = document.getElementById('placeholder-icon');

                actualBtn.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        fileChosen.textContent = file.name;
                        fileChosen.classList.add('text-blue-600', 'font-medium');
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                            placeholderIcon.classList.add('hidden');
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</x-layouts::app>
