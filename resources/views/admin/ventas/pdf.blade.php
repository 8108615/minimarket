<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Completo de Ventas</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 15px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #111; font-size: 16px; }
        .header p { margin: 5px 0 0; font-size: 10px; color: #666; }

        .venta-card { border: 1px solid #ccc; margin-bottom: 15px; padding: 10px; border-radius: 4px; background: #fafafa; }
        .venta-header { display: flex; justify-content: space-between; font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 8px; font-size: 11px; }

        .grid-info { width: 100%; margin-bottom: 8px; font-size: 10px; }
        .grid-info td { padding: 2px 4px; border: none; }

        table.detalles { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.detalles th, table.detalles td { border: 1px solid #ddd; padding: 5px; text-align: left; font-size: 10px; }
        table.detalles th { background-color: #eaeaea; color: #111; }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totales-venta { text-align: right; font-size: 11px; font-weight: bold; margin-top: 5px; }

        .total-general-box { margin-top: 20px; text-align: right; font-size: 14px; font-weight: bold; border-top: 2px solid #333; padding-top: 10px; }

        .no-print { margin-bottom: 15px; text-align: right; }
        .btn { background: #2563eb; color: #fff; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-weight: bold; font-size: 12px; }
        @media print {
            .no-print { display: none; }
            .venta-card { break-inside: avoid; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn">Imprimir / Guardar PDF</button>
    </div>

    <div class="header">
        <h2>REPORTE DETALLADO DE VENTAS (TODOS LOS CAMPOS)</h2>
        <p>Fecha de emisión: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    @php $totalGeneral = 0; @endphp
    @forelse($ventas as $venta)
        <div class="venta-card">
            <div class="venta-header">
                <span>Comprobante: {{ $venta->numero_comprobante }} ({{ $venta->tipo_comprobante }})</span>
                <span>Fecha: {{ $venta->fecha_venta ?? $venta->created_at }}</span>
                <span>Estado: {{ $venta->estado }}</span>
            </div>

            <table class="grid-info">
                <tr>
                    <td><strong>Cliente:</strong> {{ $venta->cliente ? $venta->cliente->nombres . ' ' . ($venta->cliente->apellidos ?? '') : 'Público General' }}</td>
                    <td><strong>Atendido por:</strong> {{ $venta->user->name ?? 'N/A' }}</td>
                    <td><strong>Método Pago:</strong> {{ $venta->metodo_pago }}</td>
                    <td><strong>Cod. Transacción:</strong> {{ $venta->codigo_transaccion ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Subtotal Venta:</strong> Bs. {{ number_format($venta->subtotal, 2) }}</td>
                    <td><strong>Monto Recibido:</strong> Bs. {{ number_format($venta->monto_recibido, 2) }}</td>
                    <td><strong>Vuelto Entregado:</strong> Bs. {{ number_format($venta->vuelto_entregado, 2) }}</td>
                    <td><strong>Total Venta:</strong> Bs. {{ number_format($venta->total, 2) }}</td>
                </tr>
            </table>

            <!-- Tabla de Detalles -->
            <table class="detalles">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-right">Precio Unitario (Bs.)</th>
                        <th class="text-right">Total Detalle (Bs.)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venta->detalles as $det)
                        <tr>
                            <td>{{ $det->producto->nombre ?? $det->producto->name ?? 'Producto #' . $det->producto_id }}</td>
                            <td class="text-center">{{ $det->cantidad }}</td>
                            <td class="text-right">Bs. {{ number_format($det->precio_venta, 2) }}</td>
                            <td class="text-right">Bs. {{ number_format($det->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php if($venta->estado === 'Completado') $totalGeneral += $venta->total; @endphp
    @empty
        <div style="text-align: center; padding: 20px;">No hay registros de ventas disponibles.</div>
    @endforelse

    <div class="total-general-box">
        MONTO TOTAL ACUMULADO (VENTAS COMPLETADAS): Bs. {{ number_format($totalGeneral, 2) }}
    </div>

</body>
</html>
