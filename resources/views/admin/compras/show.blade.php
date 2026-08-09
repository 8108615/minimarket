<x-layouts::app title="Detalle de la Compra">
    <style>
        @media print {
            /* Ocultar elementos de la interfaz */
            aside, nav, header, footer, .no-print, button, a {
                display: none !important;
            }
            flux\\:sidebar, flux\\:header {
                display: none !important;
            }

            /* Resetear márgenes y asegurar ancho completo en la hoja */
            html, body, main, .py-6, .max-w-7xl {
                background-color: white !important;
                color: black !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            /* Centrar el comprobante y asegurar que ocupe un ancho óptimo y simétrico */
            .max-w-3xl {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                border: none !important;
                box-shadow: none !important;
                padding: 10mm !important;
            }

            div, table, th, td {
                box-shadow: none !important;
                border-color: #cbd5e1 !important;
            }
        }
    </style>

    <!-- Botón volver (No se imprime) -->
    <div class="mb-6 flex justify-end no-print">
        <a href="{{ route('admin.compras.index') }}"
            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <!-- Contenedor del Comprobante -->
    <div class="max-w-3xl mx-auto bg-white dark:bg-zinc-800 p-8 rounded-lg border border-gray-200 dark:border-zinc-700 shadow-sm">
        
        <!-- Título al medio -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold uppercase tracking-wider text-gray-900 dark:text-gray-100">Detalle de la Compra</h1>
            <div class="w-16 h-1 bg-blue-500 mx-auto mt-2"></div>
        </div>

        <!-- Datos generales en formato vertical/ordenado -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm">
            <div class="space-y-3">
                <div>
                    <span class="font-bold text-gray-500 dark:text-gray-400 block uppercase text-xs">Proveedor:</span>
                    <span class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $compra->proveedor->nombre ?? 'Sin proveedor' }}</span>
                </div>
                <div>
                    <span class="font-bold text-gray-500 dark:text-gray-400 block uppercase text-xs">Usuario Responsable:</span>
                    <span class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $compra->user->name ?? 'N/D' }}</span>
                </div>
            </div>

            <div class="space-y-3 md:text-right">
                <div>
                    <span class="font-bold text-gray-500 dark:text-gray-400 block uppercase text-xs">Fecha y Hora:</span>
                    <span class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $compra->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="font-bold text-gray-500 dark:text-gray-400 block uppercase text-xs">Estado:</span>
                    <span class="inline-block mt-1 px-3 py-1 {{ $compra->estado == 'Completado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-semibold rounded-full">
                        {{ $compra->estado }}
                    </span>
                </div>
            </div>
        </div>

        <hr class="border-gray-200 dark:border-zinc-700 mb-6">

        <!-- Tabla de productos más abajo -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-zinc-700 mb-6">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-50 dark:bg-zinc-900 text-center">
                    <tr>
                        <th class="px-4 py-3 border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nro</th>
                        <th class="px-4 py-3 border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider text-left">Producto</th>
                        <th class="px-4 py-3 border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cantidad</th>
                        <th class="px-4 py-3 border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio Unitario</th>
                        <th class="px-4 py-3 border-b border-gray-200 dark:border-zinc-700 text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800">
                    @foreach ($compra->detalles as $detalle)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 transition">
                            <td class="px-3 py-3 border-b border-gray-200 dark:border-zinc-700 text-sm text-center">{{ $loop->iteration }}</td>
                            <td class="px-3 py-3 border-b border-gray-200 dark:border-zinc-700 text-sm font-semibold text-gray-900 dark:text-gray-100 text-left">
                                {{ $detalle->producto->nombre ?? 'Producto eliminado' }}
                            </td>
                            <td class="px-3 py-3 border-b border-gray-200 dark:border-zinc-700 text-sm text-center text-gray-700 dark:text-gray-300">
                                {{ $detalle->cantidad }}
                            </td>
                            <td class="px-3 py-3 border-b border-gray-200 dark:border-zinc-700 text-sm text-center text-gray-700 dark:text-gray-300">
                                {{ $simboloDivisa ?? 'Bs' }} {{ number_format($detalle->precio_compra, 2) }}
                            </td>
                            <td class="px-3 py-3 border-b border-gray-200 dark:border-zinc-700 text-sm text-center font-semibold text-green-600">
                                {{ $simboloDivisa ?? 'Bs' }} {{ number_format($detalle->cantidad * $detalle->precio_compra, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total General al final -->
        <div class="flex justify-end">
            <div class="w-full md:w-1/2 bg-gray-50 dark:bg-zinc-900 p-4 rounded-lg border border-gray-200 dark:border-zinc-700 flex justify-between items-center">
                <span class="font-bold text-gray-700 dark:text-gray-300 uppercase text-sm">Total General:</span>
                <span class="font-bold text-green-600 text-lg">
                    {{ $simboloDivisa ?? 'Bs' }} {{ number_format($compra->total, 2) }}
                </span>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === 'true') {
                window.print();
            }
        });
    </script>
</x-layouts::app>