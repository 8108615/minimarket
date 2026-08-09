<x-layouts::app title="Detalle de Cliente">
    <div class="mb-6 w-full">
        <flux:heading size="xl">Información del Cliente</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-sm font-bold text-gray-500 uppercase">Datos Principales</h3>
                <div class="mt-4 space-y-4">
                    <div>
                        <flux:text class="font-bold">Nombres:</flux:text>
                        <flux:text>{{ $cliente->nombres }}</flux:text>
                    </div>
                    <div>
                        <flux:text class="font-bold">Cédula de Identidad (CI):</flux:text>
                        <flux:text>{{ $cliente->ci ?? 'No registrado' }}</flux:text>
                    </div>
                    <div>
                        <flux:text class="font-bold">Estado:</flux:text>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $cliente->estado == 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $cliente->estado }}
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold text-gray-500 uppercase">Datos de Contacto</h3>
                <div class="mt-4 space-y-4">
                    <div>
                        <flux:text class="font-bold">Teléfono:</flux:text>
                        <flux:text>{{ $cliente->telefono ?? 'No registrado' }}</flux:text>
                    </div>
                    <div>
                        <flux:text class="font-bold">Email:</flux:text>
                        <flux:text>{{ $cliente->email ?? 'No registrado' }}</flux:text>
                    </div>
                    <div>
                        <flux:text class="font-bold">Dirección:</flux:text>
                        <flux:text>{{ $cliente->direccion ?? 'No registrado' }}</flux:text>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            <flux:button href="{{ route('admin.clientes.index') }}" icon="arrow-left">Volver al listado</flux:button>
        </div>
    </div>
</x-layouts::app>
