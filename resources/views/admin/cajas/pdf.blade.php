<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Caja #{{ $caja->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; color: #666; }
        .info-box { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-box td { padding: 6px; border: 1px solid #ddd; }
        .info-box th { background: #f4f4f4; padding: 6px; border: 1px solid #ddd; text-align: left; }
        table.detalles { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.detalles th, table.detalles td { border: 1px solid #ddd; padding: 6px; text-align: center; font-size: 11px; }
        table.detalles th { background: #f4f4f4; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .totales { margin-top: 15px; float: right; width: 300px; }
        .totales td { padding: 5px; border: none; }
        .print-btn { text-align: center; margin-bottom: 20px; }
        @media print {
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

    <div class="print-btn">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Imprimir / Guardar PDF</button>
    </div>

    <div class="header">
        <h2>Reporte de Turno / Caja</h2>
        <p>Minimarket - Resumen de Operaciones</p>
    </div>

    <table class="info-box">
        <tr>
            <th>ID de Caja:</th>
            <td>#{{ $caja->id }}</td>
            <th>Estado:</th>
            <td>{{ ucfirst($caja->estado) }}</td>
        </tr>
        <tr>
            <th>Cajero:</th>
            <td>{{ $caja->user->name ?? 'N/A' }}</td>
            <th>Fecha Apertura:</th>
            <td>{{ $caja->fecha_apertura }}</td>
        </tr>
        <tr>
            <th>Saldo Inicial:</th>
            <td>{{ $simboloMoneda }} {{ number_format($caja->saldo_inicial, 2) }}</td>
            <th>Fecha Cierre:</th>
            <td>{{ $caja->fecha_cierre ?? 'Caja aún en curso' }}</td>
        </tr>
    </table>

    <h3 style="margin-bottom: 5px; font-size: 14px;">Detalle de Ventas del Turno</h3>
    <table class="detalles">
        <thead>
            <tr>
                <th>Nro</th>
                <th>Comprobante</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Método Pago</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ventas as $index => $venta)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $venta->numero_comprobante }}</strong></td>
                    <td>{{ $venta->fecha_venta ?? $venta->created_at }}</td>
                    <td>{{ $venta->cliente->nombres ?? 'General' }}</td>
                    <td>{{ $venta->metodo_pago }}</td>
                    <td class="text-right" style="padding-right: 10px;">{{ $simboloMoneda }} {{ number_format($venta->total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="color: #775; padding: 15px;">No se registraron ventas en este turno.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px; float: right; width: 320px; border: 1px solid #333; padding: 10px; border-radius: 5px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 5px;"><strong>Saldo Inicial:</strong></td>
                <td style="text-align: right; padding: 5px;">{{ $simboloMoneda }} {{ number_format($caja->saldo_inicial, 2) }}</td>
            </tr>
            <tr>
                <td style="padding: 5px;"><strong>Total Vendido:</strong></td>
                <td style="text-align: right; padding: 5px;">{{ $simboloMoneda }} {{ number_format($caja->total_ventas, 2) }}</td>
            </tr>
            <tr style="border-top: 2px solid #333; background-color: #f9f9f9;">
                <td style="padding: 10px 5px 5px 5px; font-size: 14px;"><strong>SALDO FINAL:</strong></td>
                <td style="text-align: right; padding: 10px 5px 5px 5px; font-size: 14px;"><strong>{{ $simboloMoneda }} {{ number_format($caja->saldo_final, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Sección de Firmas (Forzada al pie de página) -->
    <div style="clear: both; position: fixed; bottom: 50px; width: 100%;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 45%; text-align: center; vertical-align: top;">
                    <div style="border-top: 1px solid #333; width: 80%; margin: 0 auto; padding-top: 5px;">
                        <!-- Muestra el nombre real del usuario que abrió la caja -->
                        <strong>{{ $caja->user->name ?? 'N/A' }}</strong><br>

                        <!-- Muestra dinámicamente el rol que tenga asignado (Administrador, Auxiliar, Cajero, etc.) -->
                        <span style="font-size: 10px; color: #666; text-transform: uppercase;">
                            {{ $caja->user ? $caja->user->getRoleNames()->implode(', ') : 'CAJERO(A)' }}
                        </span>
                    </div>
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%; text-align: center; vertical-align: top;">
                   <div style="border-top: 1px solid #333; width: 80%; margin: 0 auto; padding-top: 5px;">
                        <strong>{{ $superAdmin->name ?? 'No asignado' }}</strong><br>
                        <span style="font-size: 10px; color: #666; text-transform: uppercase;">
                            {{ $superAdmin ? $superAdmin->getRoleNames()->implode(', ') : 'SUPER ADMIN' }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
