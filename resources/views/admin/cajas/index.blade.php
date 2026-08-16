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
            @php
                // Verificamos si el usuario actual tiene alguna caja abierta
                $tieneCajaAbierta = \App\Models\Caja::where('user_id', Auth::id())->where('estado', 'abierto')->exists();
            @endphp

            @if($tieneCajaAbierta)
                <div class="flex items-center px-4 py-2 bg-amber-100 dark:bg-amber-900/50 border border-amber-300 dark:border-amber-700 text-amber-800 dark:text-amber-300 rounded-lg text-sm font-semibold">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Caja Abierta.
                </div>
            @else
                <!-- Botón que abre el Modal de Flux -->
                <flux:modal.trigger name="abrir-caja-modal">
                    <flux:button variant="primary" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold cursor-pointer">
                        <i class="fas fa-plus mr-2"></i> Abrir Caja
                    </flux:button>
                </flux:modal.trigger>
            @endif
        </div>
    </div>

    <!-- MODAL PARA ABRIR CAJA -->
    <flux:modal name="abrir-caja-modal" class="md:w-96 space-y-6">
        <div>
            <flux:heading size="lg">Abrir Nueva Caja</flux:heading>
            <flux:text class="mt-1">Ingresa el monto inicial con el que abrirás la caja.</flux:text>
        </div>

        <form action="{{ route('admin.cajas.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Monto Inicial ({{ $simboloMoneda }})</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-wallet"></i>
                    </span>
                    <input type="number" step="0.01" name="saldo_inicial" required
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-zinc-700 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-zinc-800 dark:text-white"
                        placeholder="0.00" value="{{ old('saldo_inicial') }}">
                </div>
                @error('saldo_inicial')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end gap-2 pt-4">
                <flux:modal.close>
                    <flux:button variant="subtle" class="cursor-pointer">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary" class="bg-blue-500 hover:bg-blue-600 text-white cursor-pointer">
                    <i class="fas fa-save mr-1"></i> Guardar
                </flux:button>
            </div>
        </form>
    </flux:modal>

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
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $simboloMoneda }} {{ number_format($caja->saldo_inicial, 2) }}</td>
                        <td class="px-3 py-2 border border-gray-200 dark:border-zinc-700 text-sm text-center">
                            {{ $simboloMoneda }} {{ number_format($caja->saldo_final, 2) }} 
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

                                <!-- Botón de Imprimir Reporte en el Index -->
                                <a href="{{ route('admin.cajas.pdf', $caja->id) }}" target="_blank"
                                    class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                                    title="Imprimir Reporte PDF">
                                    <i class="fas fa-print"></i>
                                </a>

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
