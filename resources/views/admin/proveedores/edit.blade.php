<x-layouts::app title="Editar Proveedor">
    <div class="mb-6 w-full">
        <flux:heading size="xl">Editar Proveedor</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    <form action="{{ route('admin.proveedores.update', $proveedor->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">

            {{-- Inputs del Proveedor --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Nombre / Empresa --}}
                <div>
                    <flux:label>Nombre / Empresa <span class="text-red-500">(*)</span></flux:label>
                    <flux:input name="nombre" icon="building-storefront" value="{{ old('nombre', $proveedor->nombre) }}" placeholder="Nombre del proveedor o empresa..." required />
                    <flux:error name="nombre" />
                </div>

                {{-- NIT --}}
                <div>
                    <flux:label>NIT</flux:label>
                    <flux:input name="nit" icon="identification" value="{{ old('nit', $proveedor->nit) }}" placeholder="Número de NIT..." />
                    <flux:error name="nit" />
                </div>

                {{-- Teléfono --}}
                <div>
                    <flux:label>Teléfono</flux:label>
                    <flux:input name="telefono" icon="phone" value="{{ old('telefono', $proveedor->telefono) }}" placeholder="Teléfono de contacto..." />
                    <flux:error name="telefono" />
                </div>

                {{-- Email --}}
                <div>
                    <flux:label>Correo electrónico</flux:label>
                    <flux:input name="email" type="email" icon="envelope" value="{{ old('email', $proveedor->email) }}" placeholder="correo@ejemplo.com" />
                    <flux:error name="email" />
                </div>

                {{-- Dirección --}}
                <div>
                    <flux:label>Dirección</flux:label>
                    <flux:input name="direccion" icon="map-pin" value="{{ old('direccion', $proveedor->direccion) }}" placeholder="Dirección física..." />
                    <flux:error name="direccion" />
                </div>

                {{-- Estado --}}
                <div>
                    <flux:select name="estado" label="Estado (*)" required>
                        <flux:select.option value="Activo" :selected="old('estado', $proveedor->estado) == 'Activo'">Activo</flux:select.option>
                        <flux:select.option value="Inactivo" :selected="old('estado', $proveedor->estado) == 'Inactivo'">Inactivo</flux:select.option>
                    </flux:select>
                    <flux:error name="estado" />
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <flux:button href="{{ route('admin.proveedores.index') }}">Cancelar</flux:button>
                <flux:button type="submit" variant="primary" class="px-5 cursor-pointer" color="green"><i class="fas fa-save"></i> Actualizar Proveedor</flux:button>
            </div>
        </div>
    </form>
</x-layouts::app>
