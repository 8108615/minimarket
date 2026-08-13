<x-layouts::app title="Gestión de Cajas">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">Gestión de Cajas</flux:heading>
        <br>
        <flux:separator variant="subtle" />
    </div>

    <div class="flex gap-4">
        <div class="flex-1">
            <form action="{{ route('admin.cajas.index') }}" method="GET" class="flex gap-2 w-1/2">
                <div class="flex-1">
                    <flux:input name="buscar" type="text" icon="magnifying-glass" placeholder="Buscar por estado o cajero..."
                        value="{{ request('buscar') }}" class="transition-all duration-200" />
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-search"></i> Buscar
                </button>
                @if (request('buscar'))
                    <a href="{{ route('admin.cajas.index') }}"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash"></i> Limpiar
                    </a>
                @endif
            </form>
        </div>

        <div class="flex-1 justify-end flex">
            <a href="{{ route('admin.cajas.create') }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition flex items-center gap-2" wire:navigate>
                <i class="fas fa-plus mr-2"></i> Abrir Caja
            </a>
        </div>
    </div>

    @if (request('buscar'))
        <div class="mt-4 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg">
            <p class="text-xl text-gray-700 dark:text-gray-300">
                <i class="fas fa-search mr-2"></i>
                Se {{ $cajas->total() == 1 ? 'encontró' : 'encontraron' }}
                <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $cajas->total() }}</span>
                {{ $cajas->total() == 1 ? 'resultado' : 'resultados' }}
                con la búsqueda: <span class="font-semibold">"{{ request('buscar') }}"</span>
            </p>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 mt-6">
        <table class="min-w-full border-collapse">
            <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                <tr>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nro</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cajero</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Saldo Inicial</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Saldo Final</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Apertura</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha Cierre</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 border-x border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-800">
                @forelse ($cajas as $caja)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $loop->iteration + ($cajas->currentPage() - 1) * $cajas->perPage() }}</td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm font-semibold">{{ $caja->user->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">${{ number_format($caja->saldo_inicial, 2) }}</td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">
                            {{ $caja->saldo_final ? '$' . number_format($caja->saldo_final, 2) : '-' }}
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $caja->fecha_apertura }}</td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $caja->fecha_cierre ?? 'En curso' }}</td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $caja->estado == 'abierto' ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' }}">
                                {{ ucfirst($caja->estado) }}
                            </span>
                        </td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-center">
                            <div class="flex justify-center gap-2 items-center">
                                <a href="{{ route('admin.cajas.show', $caja->id) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded transition" wire:navigate><i class="fas fa-eye mr-1"></i> Ver</a>

                                @if($caja->estado == 'abierto')
                                    <button type="button" onclick="confirmarCierre{{ $caja->id }}()" class="inline-flex items-center px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded transition cursor-pointer">
                                        <i class="fas fa-lock mr-1"></i> Cerrar
                                    </button>
                                    <form action="{{ route('admin.cajas.cerrar', $caja->id) }}" method="POST" id="formCerrarCaja{{ $caja->id }}" class="hidden">
                                        @csrf
                                    </form>
                                    <script>
                                        function confirmarCierre{{ $caja->id }}() {
                                            Swal.fire({
                                                title: '¿Deseas cerrar esta caja?',
                                                text: "Se registrará el cierre de la caja actual.",
                                                icon: 'question',
                                                showDenyButton: true,
                                                confirmButtonText: 'Sí, cerrar',
                                                confirmButtonColor: '#10b981',
                                                denyButtonText: 'Cancelar'
                                            }).then((result) => {
                                                if (result.isConfirmed) document.getElementById('formCerrarCaja{{ $caja->id }}').submit();
                                            });
                                        }
                                    </script>
                                @endif

                                <form action="{{ route('admin.cajas.destroy', $caja->id) }}" method="POST" id="formCaja{{ $caja->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition cursor-pointer" onclick="confirmarEliminacion{{ $caja->id }}()">
                                        <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                    </button>
                                </form>
                                <script>
                                    function confirmarEliminacion{{ $caja->id }}() {
                                        Swal.fire({
                                            title: '¿Eliminar registro de caja?',
                                            icon: 'question',
                                            showDenyButton: true,
                                            confirmButtonText: 'Eliminar',
                                            confirmButtonColor: '#a5161d',
                                            denyButtonText: 'Cancelar'
                                        }).then((result) => { if (result.isConfirmed) document.getElementById('formCaja{{ $caja->id }}').submit(); });
                                    }
                                </script>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">No se encontraron cajas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($cajas->hasPages())
        <div class="px-3 mt-4">{{ $cajas->links() }}</div>
    @endif
</x-layouts::app>
