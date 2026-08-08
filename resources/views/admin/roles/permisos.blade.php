<x-layouts::app title="Asignar Permisos">
    <div class="relative mb-6 w-full flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <flux:heading size="xl" level="1">Asignar Permisos al Rol: <span class="text-blue-600 dark:text-blue-400">{{ $rol->name }}</span></flux:heading>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Selecciona los permisos que tendrá este rol por cada módulo del sistema.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" id="select-all-btn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                Seleccionar todos
            </button>
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                Volver
            </a>
        </div>
    </div>

    <flux:separator variant="subtle" class="mb-6" />

    <form action="{{ route('admin.roles.guardar_permisos', $rol->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Grid de Módulos (Tarjetas) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach ($permisos as $modulo => $perms)
                @php
                    // Formatear el nombre del módulo para que luzca bien (ej. Rc_iva -> RC-IVA)
                    $moduloVisual = strtoupper(str_replace('_', '-', $modulo));
                @endphp
                <div class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-xl p-5 shadow-sm flex flex-col justify-between">
                    <div>
                        <!-- Cabecera de la tarjeta del módulo -->
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100 dark:border-zinc-700">
                            <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wide flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                                {{ $moduloVisual }}
                            </h3>
                            <button type="button" class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium select-card-btn" data-target="card-{{ Str::slug($modulo) }}">
                                Seleccionar todos
                            </button>
                        </div>

                        <!-- Lista de permisos del módulo con nombres amigables -->
                        <div class="space-y-2 card-{{ Str::slug($modulo) }}">
                            @foreach ($perms as $permiso)
                                @php
                                    // Extraemos la acción (ej. index, create, destroy, etc.)
                                    $partes = explode('.', $permiso->name);
                                    $accion = end($partes);

                                    // Diccionario de traducción para las acciones comunes
                                    $traduccionesAcciones = [
                                        'index'   => 'Ver listado',
                                        'create'  => 'Crear',
                                        'store'   => 'Guardar',
                                        'show'    => 'Ver detalles',
                                        'edit'    => 'Editar',
                                        'update'  => 'Actualizar',
                                        'destroy' => 'Eliminar',
                                        'pdf'     => 'Descargar PDF',
                                        'print'   => 'Imprimir',
                                        'pagar'   => 'Marcar como pagado',
                                        'estado'  => 'Cambiar estado',
                                        'permisos'=> 'Asignar permisos',
                                        'calcular'=> 'Calcular masivo',
                                        'imprimir'=> 'Imprimir documento'
                                    ];

                                    $nombreAmigable = $traduccionesAcciones[$accion] ?? ucfirst(str_replace('_', ' ', $accion));
                                    $moduloSingular = rtrim(strtolower($moduloVisual), 's');

                                    if ($accion == 'index') {
                                        $textoFinal = "Ver listado de " . strtolower($moduloVisual);
                                    } elseif ($accion == 'create' || $accion == 'store') {
                                        $textoFinal = "Crear / Guardar " . $moduloSingular;
                                    } elseif ($accion == 'edit' || $accion == 'update') {
                                        $textoFinal = "Editar " . $moduloSingular;
                                    } elseif ($accion == 'destroy') {
                                        $textoFinal = "Eliminar " . $moduloSingular;
                                    } elseif ($accion == 'permisos') {
                                        $textoFinal = "Asignar permisos al " . $moduloSingular;
                                    } else {
                                        $textoFinal = $nombreAmigable . " de " . strtolower($moduloVisual);
                                    }
                                @endphp

                                <label class="flex items-center space-x-3 p-2.5 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700/50 cursor-pointer transition">
                                    <input type="checkbox" name="permisos[]" value="{{ $permiso->name }}"
                                        {{ $rol->hasPermissionTo($permiso->name) ? 'checked' : '' }}
                                        class="permission-checkbox rounded border-gray-300 dark:border-zinc-600 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-zinc-900 w-4 h-4">
                                    <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ ucfirst($textoFinal) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Botones inferiores de acción -->
        <div class="flex justify-end gap-3 sticky bottom-4 bg-gray-50/80 dark:bg-zinc-900/80 backdrop-blur-md p-4 rounded-xl border border-gray-200 dark:border-zinc-800 shadow-lg">
            <a href="{{ route('admin.roles.index') }}" class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition text-sm">
                Cancelar
            </a>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition text-sm shadow-sm">
                Guardar Permisos
            </button>
        </div>
    </form>

    <!-- Script para los botones de "Seleccionar todos" -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const globalBtn = document.getElementById('select-all-btn');
            globalBtn.addEventListener('click', function () {
                const checkboxes = document.querySelectorAll('.permission-checkbox');
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                checkboxes.forEach(cb => cb.checked = !allChecked);
                globalBtn.textContent = allChecked ? 'Seleccionar todos' : 'Deseleccionar todos';
            });

            document.querySelectorAll('.select-card-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const targetClass = this.getAttribute('data-target');
                    const container = document.querySelector('.' + targetClass);
                    const checkboxes = container.querySelectorAll('.permission-checkbox');
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

                    checkboxes.forEach(cb => cb.checked = !allChecked);
                    this.textContent = allChecked ? 'Seleccionar todos' : 'Deseleccionar todos';
                });
            });
        });
    </script>
</x-layouts::app>
