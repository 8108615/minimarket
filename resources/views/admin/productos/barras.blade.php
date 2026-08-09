<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Impresión Masiva de Códigos de Barras</title>
    <!-- JsBarcode CDN para generar los códigos de barras automáticamente -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #000;
        }
        .no-print {
            margin-bottom: 20px;
            text-align: center;
        }
        .btn {
            padding: 10px 20px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #1d4ed8;
        }
        /* Contenedor en grilla para las etiquetas */
        .grid-etiquetas {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }
        .etiqueta {
            width: 220px;
            border: 1px dashed #ccc;
            padding: 10px;
            text-align: center;
            background: #fff;
            box-sizing: border-box;
            page-break-inside: avoid;
        }
        .nombre-producto {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .precio-producto {
            font-size: 12px;
            font-weight: bold;
            color: #16a34a;
            margin-top: 4px;
        }
        /* Ocultar elementos de navegación/botones al mandar a imprimir */
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
            .etiqueta {
                border: 1px solid #000; /* Borde sólido opcional para guías de corte */
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn" onclick="window.print()">🖨️ Imprimir Etiquetas</button>
        <a href="{{ route('admin.productos.index') }}" style="margin-left: 10px; text-decoration: none; color: #64748b;">Regresar</a>
    </div>

    <div class="grid-etiquetas">
        @foreach($productos as $producto)
            <div class="etiqueta">
                <div class="nombre-producto" title="{{ $producto->nombre }}">{{ $producto->nombre }}</div>
                <!-- Elemento SVG donde se dibuja el código de barras -->
                <svg class="barcode"
                    data-value="{{ $producto->codigo }}"
                    data-format="CODE128"
                    data-width="1.3"
                    data-height="40"
                    data-font-size="11"
                    data-display-value="true">
                </svg>
                <div class="precio-producto">Precio: {{ number_format($producto->precio_venta, 2) }}</div>
            </div>
        @endforeach
    </div>

    <script>
        // Generar automáticamente todos los códigos de barras usando JsBarcode
        document.addEventListener("DOMContentLoaded", function() {
            const barcodes = document.querySelectorAll(".barcode");
            barcodes.forEach(function(element) {
                const value = element.getAttribute("data-value");
                if(value) {
                    JsBarcode(element, value, {
                        format: element.getAttribute("data-format"),
                        width: parseFloat(element.getAttribute("data-width")),
                        height: parseInt(element.getAttribute("data-height")),
                        displayValue: true,
                        fontSize: parseInt(element.getAttribute("data-font-size")),
                        margin: 2
                    });
                }
            });
        });
    </script>
</body>
</html>
