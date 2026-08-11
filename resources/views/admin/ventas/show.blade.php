<x-layouts::app.sidebar title="Detalles de la Venta">
    <flux:main>
        <div class="max-w-2xl mx-auto p-6 bg-white dark:bg-zinc-800 rounded-xl shadow-md border border-gray-200 dark:border-zinc-700 my-6">

            <!-- Botones de Acción (No se imprimen) -->
            <div class="flex justify-between items-center mb-6 print:hidden">
                <a href="{{ route('admin.ventas.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold rounded-lg transition">
                    &larr; Volver al Listado
                </a>
                <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimir Comprobante
                </button>
            </div>

            <!-- Cabecera del Ticket -->
            <div class="text-center pb-6 border-b border-gray-300 dark:border-zinc-700">
                <h2 class="text-xl font-bold uppercase tracking-wider text-gray-800 dark:text-white">SISTEMA DE VENTAS</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Comprobante Electrónico</p>
                <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 mt-2">
                    {{ strtoupper($venta->tipo_comprobante) }}: {{ $venta->numero_comprobante }}
                </p>
            </div>

            <!-- Datos Generales -->
            <div class="py-4 text-xs text-gray-600 dark:text-gray-300 space-y-1.5 border-b border-gray-300 dark:border-zinc-700">
                <div class="flex justify-between">
                    <span class="font-bold">Fecha y Hora:</span>
                    <span>{{ $venta->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold">Cliente:</span>
                    <span>{{ $venta->cliente ? $venta->cliente->nombres . ' ' . ($venta->cliente->apellidos ?? '') : 'Público General' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold">Atendido por:</span>
                    <span>{{ $venta->user->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold">Método de Pago:</span>
                    <span class="uppercase font-semibold text-emerald-600 dark:text-emerald-400">{{ $venta->metodo_pago }}</span>
                </div>
                @if($venta->codigo_transaccion)
                    <div class="flex justify-between">
                        <span class="font-bold">N° Transacción / Ref:</span>
                        <span>{{ $venta->codigo_transaccion }}</span>
                    </div>
                @endif
            </div>

            <!-- Tabla de Productos -->
            <div class="py-4">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-300 dark:border-zinc-700 text-gray-500 dark:text-gray-400 uppercase">
                            <th class="py-2">Cant / Producto</th>
                            <th class="py-2 text-right">P. Unit</th>
                            <th class="py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-700 text-gray-700 dark:text-gray-200">
                        @foreach($venta->detalles as $detalle)
                            <tr>
                                <td class="py-2.5">
                                    <span class="font-bold">{{ $detalle->cantidad }}x</span> {{ $detalle->producto->nombre ?? 'Producto eliminado' }}
                                </td>
                                <td class="py-2.5 text-right">{{ number_format($detalle->precio_venta, 2) }}</td>
                                <td class="py-2.5 text-right font-semibold">{{ number_format($detalle->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totales -->
            <div class="pt-4 border-t border-gray-300 dark:border-zinc-700 text-sm space-y-1.5 text-gray-700 dark:text-gray-200">
                <div class="flex justify-between font-bold text-base">
                    <span>TOTAL A PAGAR:</span>
                    <span class="text-emerald-600 dark:text-emerald-400">Bs. {{ number_format($venta->total, 2) }}</span>
                </div>
                @if($venta->metodo_pago === 'Efectivo')
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>Efectivo Recibido:</span>
                        <span>Bs. {{ number_format($venta->monto_recibido, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>Vuelto Entregado:</span>
                        <span>Bs. {{ number_format($venta->vuelto_entregado, 2) }}</span>
                    </div>
                @endif
            </div>

            <!-- Pie de Ticket -->
            <div class="mt-8 text-center text-xs text-gray-400 dark:text-gray-500 border-t border-dashed border-gray-300 dark:border-zinc-700 pt-4">
                <p>¡Gracias por su compra!</p>
                <p class="mt-1">Conserve este comprobante para cualquier reclamo o devolución.</p>
            </div>

        </div>
    </flux:main>
</x-layouts::app.sidebar>

@push('styles')
<style>
    @media print {
        body {
            background: white !important;
            color: black !important;
        }
        aside, nav, header, footer {
            display: none !important;
        }
    }
</style>
@endpush
