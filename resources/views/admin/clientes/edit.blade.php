<x-layouts::app title="Editar Cliente">
    <div class="mb-6 w-full">
        <flux:heading size="xl">Editar Cliente</flux:heading>
        <flux:separator variant="subtle" class="my-4" />
    </div>

    <form action="{{ route('admin.clientes.update', $cliente->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white dark:bg-neutral-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700">

            {{-- Inputs del Cliente --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Nombres --}}
                <div>
                    <flux:label>Nombres <span class="text-red-500">(*)</span></flux:label>
                    <flux:input name="nombres" icon="user" value="{{ old('nombres', $cliente->nombres) }}" placeholder="Nombre del cliente..." required />
                    <flux:error name="nombres" />
                </div>

                {{-- CI --}}
                <div>
                    <flux:label>CI</flux:label>
                    <flux:input name="ci" icon="identification" value="{{ old('ci', $cliente->ci) }}" placeholder="Cédula de identidad..." />
                    <flux:error name="ci" />
                </div>

                {{-- Teléfono --}}
                <div>
                    <flux:label>Teléfono</flux:label>
                    <flux:input name="telefono" icon="phone" value="{{ old('telefono', $cliente->telefono) }}" placeholder="Teléfono de contacto..." />
                    <flux:error name="telefono" />
                </div>

                {{-- Email --}}
                <div>
                    <flux:label>Correo electrónico</flux:label>
                    <flux:input name="email" type="email" icon="envelope" value="{{ old('email', $cliente->email) }}" placeholder="correo@ejemplo.com" />
                    <flux:error name="email" />
                </div>

                {{-- Dirección --}}
                <div>
                    <flux:label>Dirección</flux:label>
                    <flux:input name="direccion" icon="map-pin" value="{{ old('direccion', $cliente->direccion) }}" placeholder="Dirección física..." />
                    <flux:error name="direccion" />
                </div>

                {{-- Estado --}}
                <div>
                    <flux:select name="estado" label="Estado (*)" required>
                        <flux:select.option value="Activo" :selected="old('estado', $cliente->estado) == 'Activo'">Activo</flux:select.option>
                        <flux:select.option value="Inactivo" :selected="old('estado', $cliente->estado) == 'Inactivo'">Inactivo</flux:select.option>
                    </flux:select>
                    <flux:error name="estado" />
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <flux:button href="{{ route('admin.clientes.index') }}">Cancelar</flux:button>
                <flux:button type="submit" variant="primary" class="px-5 cursor-pointer" color="green"><i class="fas fa-save"></i> Actualizar Cliente</flux:button>
            </div>
        </div>
    </form>
</x-layouts::app>
