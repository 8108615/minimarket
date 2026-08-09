<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Compras</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        h2 { text-align: center; margin-bottom: 5px; color: #111; font-size: 14px; }
        .fecha { text-align: center; font-size: 9px; color: #666; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 5px; text-align: center; vertical-align: middle; }
        th { background-color: #f4f4f4; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        td { font-size: 9px; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .badge { padding: 2px 6px; border-radius: 4px; font-size: 8px; font-weight: bold; }
        .bg-success { background-color: #d1fae5; color: #065f46; }
        .bg-danger { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <h2>Reporte General de Compras</h2>
    <div class="fecha">Generado el: {{ date('d/m/Y H:i:s') }}</div>

    <table>
        <thead>
            <tr>
                <th>Nro</th>
                <th>Proveedor</th>
                <th>Usuario</th>
                <th>Producto</th>
                <th>Cant.</th>
                <th>P. Unitario</th>
                <th>Fecha de Compra</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($compras as $compra)
                @php $totalDetalles = $compra->detalles->count(); @endphp
                @foreach($compra->detalles as $index => $detalle)
                    <tr>
                        @if($index === 0)
                            <td rowspan="{{ $totalDetalles }}">{{ $loop->parent->iteration }}</td>
                            <td rowspan="{{ $totalDetalles }}" class="text-left">{{ $compra->proveedor->nombre ?? 'Sin proveedor' }}</td>
                            <td rowspan="{{ $totalDetalles }}">{{ $compra->user->name ?? 'N/D' }}</td>
                        @endif

                        <td class="text-left">{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td class="text-right">Bs {{ number_format($detalle->precio_compra, 2) }}</td>

                        @if($index === 0)
                            <td rowspan="{{ $totalDetalles }}">{{ $compra->created_at->format('d/m/Y H:i') }}</td>
                            <td rowspan="{{ $totalDetalles }}" class="text-right font-semibold">Bs {{ number_format($compra->total, 2) }}</td>
                            <td rowspan="{{ $totalDetalles }}">
                                <span class="badge {{ $compra->estado == 'Completado' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $compra->estado }}
                                </span>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>