<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Productos Completo</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        h2 { text-align: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px; text-align: left; word-break: break-word; }
        th { background-color: #f1f5f9; font-weight: bold; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>Listado General de Productos</h2>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th class="text-right">P. Compra</th>
                <th class="text-right">P. Venta</th>
                <th class="text-center">Stock</th>
                <th class="text-center">Mín</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
                <tr>
                    <td class="text-center font-mono">{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->categoria->nombre ?? 'Sin categoría' }}</td>
                    <td class="text-right">{{ number_format($producto->precio_compra, 2) }}</td>
                    <td class="text-right">{{ number_format($producto->precio_venta, 2) }}</td>
                    <td class="text-center">{{ $producto->stock }}</td>
                    <td class="text-center">{{ $producto->stock_minimo }}</td>
                    <td class="text-center">{{ $producto->estado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
