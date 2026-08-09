<x-layouts::app title="Detalles del Proveedor">
    <div class="mb-6">
        <flux:breadcrumbs>
            <flux:breadcrumbs.item>Datos del proveedor: {{ $proveedor->nombre }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>

    <flux:card class="mb-6">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center overflow-hidden border border-blue-200 dark:border-blue-800">
                <flux:icon.building-storefront class="h-8 w-8 text-blue-600 dark:text-blue-400" />
            </div>

            <div>
                <flux:heading size="xl" class="uppercase text-blue-600 dark:text-blue-400">{{ $proveedor->nombre }}</flux:heading>
                <div class="flex items-center gap-3 mt-1">
                    <flux:badge size="sm">ID: #{{ $proveedor->id }}</flux:badge>
                    <flux:badge color="{{ $proveedor->estado == 'Inactivo' ? 'red' : 'green' }}" icon="{{ $proveedor->estado == 'Inactivo' ? 'x-mark' : 'check' }}">
                        {{ $proveedor->estado }}
                    </flux:badge>
                </div>
            </div>
        </div>
    </flux:card>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <flux:card>
            <flux:heading icon="building-storefront" class="mb-4">Información General</flux:heading>

            <div class="space-y-4">
                <div>
                    <flux:subheading>NOMBRE / EMPRESA</flux:subheading>
                    <flux:text>{{ $proveedor->nombre }}</flux:text>
                </div>
                <div>
                    <flux:subheading>NIT</flux:subheading>
                    <flux:text>{{ $proveedor->nit ?? 'S/N' }}</flux:text>
                </div>
                <div>
                    <flux:subheading>TELÉFONO</flux:subheading>
                    <flux:text>{{ $proveedor->telefono ?? 'No registrado' }}</flux:text>
                </div>
            </div>
        </flux:card>

        <flux:card>
            <flux:heading icon="map-pin" class="mb-4">Contacto y Ubicación</flux:heading>

            <div class="space-y-4">
                <div>
                    <flux:subheading>CORREO ELECTRÓNICO</flux:subheading>
                    <flux:text>{{ $proveedor->email ?? 'No registrado' }}</flux:text>
                </div>
                <div>
                    <flux:subheading>DIRECCIÓN</flux:subheading>
                    <flux:text>{{ $proveedor->direccion ?? 'No registrada' }}</flux:text>
                </div>
                <div>
                    <flux:label>Fecha y hora de registro</flux:label>
                    <p class="mt-1 text-gray-800 dark:text-gray-200 flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-500"></i>
                        {{ $proveedor->created_at }}
                    </p>
                </div>
            </div>
        </flux:card>

    </div>

    <div class="mt-6">
        <flux:button href="{{ route('admin.proveedores.index') }}" icon="arrow-left">Volver al listado</flux:button>
    </div>
</x-layouts::app>
