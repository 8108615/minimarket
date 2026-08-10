<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; padding: 10px; }

        /* Título Principal */
        .header-title {
            background-color: #111;
            color: #fff;
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            padding: 8px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        /* Tablas de información */
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #444; padding: 6px 8px; vertical-align: middle; }

        /* Encabezados de secciones */
        .section-header {
            background-color: #222;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
            text-transform: uppercase;
        }

        /* Tabla de Detalles de Productos */
        .table-items th {
            background-color: #111;
            color: #fff;
            font-size: 10px;
            text-transform: uppercase;
            text-align: center;
        }
        .table-items td {
            font-size: 10px;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }

        /* Totales */
        .total-row td {
            font-weight: bold;
            background-color: #f4f4f4;
        }

        /* Salto de página entre compras si es un reporte masivo */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    @foreach($compras as $compra)
    <div class="container">
        <!-- Título -->
        <div class="header-title">Orden de Compra / Adquisición</div>

        <!-- Secciones de Datos (Proveedor y Datos Generales) -->
        <table>
            <tr>
                <td class="section-header" style="width: 50%;">Datos del Proveedor</td>
                <td class="section-header" style="width: 50%;">Datos de la Transacción</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">
                    <strong>Nombre:</strong> {{ $compra->proveedor->nombre ?? 'Sin proveedor' }}<br>
                    <strong>Teléfono:</strong> {{ $compra->proveedor->telefono ?? 'N/D' }}<br>
                    <strong>Dirección:</strong> {{ $compra->proveedor->direccion ?? 'N/D' }}
                </td>
                <td style="vertical-align: top;">
                    <strong>Usuario Responsable:</strong> {{ $compra->user->name ?? 'N/D' }}<br>
                    <strong>Fecha de Compra:</strong> {{ $compra->created_at->format('d/m/Y H:i') }}<br>
                    <strong>Estado:</strong> {{ $compra->estado }}
                </td>
            </tr>
        </table>

        <!-- Tabla de Artículos / Productos -->
        <table class="table-items">
            <thead>
                <tr>
                    <th style="width: 8%;">Nro</th>
                    <th class="text-left" style="width: 42%;">Producto</th>
                    <th style="width: 12%;">Cantidad</th>
                    <th style="width: 18%;">Precio Unitario</th>
                    <th style="width: 20%;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compra->detalles as $index => $detalle)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-left">{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</td>
                        <td class="text-center">{{ $detalle->cantidad }}</td>
                        <td class="text-right">Bs {{ number_format($detalle->precio_compra, 2) }}</td>
                        <td class="text-right">Bs {{ number_format($detalle->cantidad * $detalle->precio_compra, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <!-- Suma Total -->
                <tr class="total-row">
                    <td colspan="4" class="text-right">TOTAL GENERAL:</td>
                    <td class="text-right" style="color: #065f46;">Bs {{ number_format($compra->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
    @endforeach

</body>
</html>
