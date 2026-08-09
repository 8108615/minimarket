<x-layouts::app title="Listado de Proveedores">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Proveedores del sistema</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ route('admin.proveedores.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por nombre, NIT o teléfono..."
                        value="{{ request('buscar') }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ route('admin.proveedores.index') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="flex-1 justify-end flex">
            <a href="{{ route('admin.proveedores.create') }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus mr-2"></i> Crear nuevo
            </a>
        </div>
    </div>

    @if (request('buscar'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-xl text-gray-700 dark:text-gray-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $proveedores->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $proveedores->total() }}</span>
                {{ $proveedores->total() == 1 ? 'resultado' : 'resultados' }}
                con la búsqueda: <span class="font-semibold">"{{ request('buscar') }}"</span>
            </p>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nro</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre / Empresa</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">NIT</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Teléfono</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @forelse ($proveedores as $proveedor)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-center">
                            {{ $loop->iteration + ($proveedores->currentPage() - 1) * $proveedores->perPage() }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            <div class="font-semibold">{{ $proveedor->nombre }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $proveedor->direccion ?? 'Sin dirección' }}</div>
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-center text-gray-900 dark:text-gray-100">
                            {{ $proveedor->nit ?? 'S/N' }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-center text-gray-900 dark:text-gray-100">
                            {{ $proveedor->telefono ?? '-' }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $proveedor->email ?? '-' }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            @if ($proveedor->estado == 'Activo')
                                <span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 text-xs font-semibold rounded-full">Activo</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 text-xs font-semibold rounded-full">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 whitespace-nowrap text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.proveedores.show', $proveedor->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </a>
                                <a href="{{ route('admin.proveedores.edit', $proveedor->id) }}"
                                    class="inline-flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition">
                                    <i class="fas fa-pencil-alt mr-1"></i> Editar
                                </a>
                                <form action="{{ route('admin.proveedores.destroy', $proveedor->id) }}" method="POST" id="formProveedor{{ $proveedor->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition cursor-pointer"
                                        onclick="confirmarEliminacionProveedor{{ $proveedor->id }}(event)">
                                        <i class="fas fa-trash-alt mr-2"></i> Eliminar
                                    </button>
                                </form>

                                <script>
                                    function confirmarEliminacionProveedor{{ $proveedor->id }}(event) {
                                        event.preventDefault();
                                        Swal.fire({
                                            title: '¿Desea eliminar este proveedor? ' + '{{ $proveedor->nombre }}',
                                            icon: 'question',
                                            showDenyButton: true,
                                            confirmButtonText: 'Eliminar',
                                            confirmButtonColor: '#a5161d',
                                            denyButtonColor: '#270a0a',
                                            denyButtonText: 'Cancelar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById('formProveedor{{ $proveedor->id }}').submit();
                                            }
                                        });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron proveedores registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($proveedores->hasPages())
        <div class="px-3 mt-4 flex justify-between items-center">
            <div class="text-gray-600 dark:text-gray-400 text-sm">
                Mostrando <span class="font-semibold">{{ $proveedores->firstItem() }}</span>
                al <span class="font-semibold">{{ $proveedores->lastItem() }}</span>
                de <span class="font-semibold">{{ $proveedores->total() }}</span> resultados.
            </div>
            <div>
                {{ $proveedores->links() }}
            </div>
        </div>
    @endif
</x-layouts::app>
