<x-layouts::app title="Detalles del Producto">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item>Datos del producto: {{ $producto->nombre }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <flux:card class="mb-6">
        <div class="flex items-center gap-4">
            <div class="h-20 w-20 rounded-2xl bg-zinc-100 dark:bg-zinc-900 flex items-center justify-center overflow-hidden border border-zinc-200 dark:border-zinc-700 shadow-inner">
                @if($producto->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen" class="h-full w-full object-cover">
                @else
                    <flux:icon name="photo" class="h-10 w-10 text-zinc-400" />
                @endif
            </div>

            <div>
                <flux:heading size="xl" class="uppercase text-blue-600 dark:text-blue-400">{{ $producto->nombre }}</flux:heading>
                <div class="flex items-center gap-3 mt-1">
                    <flux:badge size="sm">ID: #{{ $producto->id }}</flux:badge>
                    <flux:badge color="{{ $producto->estado == 'Inactivo' ? 'red' : 'green' }}" icon="{{ $producto->estado == 'Inactivo' ? 'x-mark' : 'check' }}">
                        {{ $producto->estado }}
                    </flux:badge>
                </div>
            </div>
        </div>
    </flux:card>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Columna 1: Información General y Precios -->
        <flux:card>
            <flux:heading icon="cube" class="mb-4">Información del Producto</flux:heading>

            <div class="space-y-4">
                <div>
                    <flux:subheading>CÓDIGO DE BARRA / INTERNO</flux:subheading>
                    <flux:text class="font-mono font-semibold">{{ $producto->codigo }}</flux:text>
                </div>

                <div>
                    <flux:subheading>CATEGORÍA</flux:subheading>
                    <flux:text>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</flux:text>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <flux:subheading>PRECIO DE COMPRA</flux:subheading>
                        <flux:text class="font-semibold text-gray-700 dark:text-gray-300">
                            {{ $simboloDivisa }} {{ number_format($producto->precio_compra, 2) }}
                        </flux:text>
                    </div>
                    <div>
                        <flux:subheading>PRECIO DE VENTA</flux:subheading>
                        <flux:text class="font-semibold text-green-600 dark:text-green-400">
                            {{ $simboloDivisa }} {{ number_format($producto->precio_venta, 2) }}
                        </flux:text>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Columna 2: Stock, Fechas y Descripción -->
        <flux:card>
            <flux:heading icon="circle-stack" class="mb-4">Inventario y Detalles</flux:heading>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <flux:subheading>STOCK ACTUAL</flux:subheading>
                        <div class="mt-1">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $producto->stock <= $producto->stock_minimo ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' }}">
                                {{ $producto->stock }} unidades
                            </span>
                        </div>
                    </div>
                    <div>
                        <flux:subheading>STOCK MÍNIMO (ALERTA)</flux:subheading>
                        <flux:text>{{ $producto->stock_minimo }} unidades</flux:text>
                    </div>
                </div>

                <div>
                    <flux:subheading>DESCRIPCIÓN</flux:subheading>
                    <flux:text class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $producto->descripcion ?: 'Sin descripción registrada.' }}
                    </flux:text>
                </div>

                <div>
                    <flux:label>Fecha y hora de registro</flux:label>
                    <p class="mt-1 text-gray-800 dark:text-gray-200 flex items-center text-sm">
                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                        {{ $producto->created_at }}
                    </p>
                </div>
            </div>
        </flux:card>

    </div>

    <div class="mt-6">
        <flux:button href="{{ route('admin.productos.index') }}" icon="arrow-left">Volver al listado</flux:button>
    </div>
</x-layouts::app>
