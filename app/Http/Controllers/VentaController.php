<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    // Listado de ventas con buscador y paginación
    public function index(Request $request)
    {
        $busqueda = $request->get('busqueda');

        $ventas = Venta::with(['cliente', 'user'])
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where('numero_comprobante', 'like', "%{$busqueda}%")
                             ->orWhereHas('cliente', function ($q) use ($busqueda) {
                                 $q->where('nombres', 'like', "%{$busqueda}%")
                                   ->orWhere('apellidos', 'like', "%{$busqueda}%");
                             });
            })
            ->latest('fecha_venta')
            ->paginate(10);

        return view('admin.ventas.index', compact('ventas', 'busqueda'));
    }

    // Vista para crear una nueva venta
    public function create()
    {
        $clientes = Cliente::all();
        $productos = Producto::where('stock', '>', 0)->get();

        // Leer el símbolo de la moneda desde public/divisas.json
        $simboloMoneda = 'Bs.'; // Valor por defecto
        $pathDivisas = public_path('divisas.json');
        if (file_exists($pathDivisas)) {
            $divisasData = json_decode(file_get_contents($pathDivisas), true);
            // Ajusta la clave según cómo tengas estructurado tu JSON (ej. 'simbolo' o 'currency')
            $simboloMoneda = $divisasData['simbolo'] ?? ($divisasData[0]['simbolo'] ?? 'Bs.');
        }

        return view('admin.ventas.create', compact('clientes', 'productos', 'simboloMoneda'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'tipo_comprobante' => 'required|in:Boleta,Factura',
            'metodo_pago' => 'required|in:Efectivo,QR,Tarjeta',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_venta' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $subtotal = 0;
            foreach ($request->productos as $item) {
                $subtotal += $item['cantidad'] * $item['precio_venta'];
            }

            // Generar número de comprobante único según el tipo (BO-0001 o FA-0001)
            $prefijo = $request->tipo_comprobante === 'Factura' ? 'FA-' : 'BO-';
            $ultimaVenta = Venta::where('tipo_comprobante', $request->tipo_comprobante)->max('id') ?? 0;
            $numeroComprobante = $prefijo . str_pad($ultimaVenta + 1, 4, '0', STR_PAD_LEFT);

            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => Auth::id(),
                'tipo_comprobante' => $request->tipo_comprobante,
                'numero_comprobante' => $numeroComprobante,
                'metodo_pago' => $request->metodo_pago,
                'codigo_transaccion' => $request->codigo_transaccion,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'monto_recibido' => $request->monto_recibido ?? $subtotal,
                'vuelto_entregado' => $request->metodo_pago === 'Efectivo' ? (($request->monto_recibido ?? $subtotal) - $subtotal) : 0,
                'estado' => 'Completado'
            ]);

            foreach ($request->productos as $item) {
                $producto = Producto::findOrFail($item['id']);

                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto: {$producto->nombre}");
                }

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_venta' => $item['precio_venta'],
                    'subtotal' => $item['cantidad'] * $item['precio_venta']
                ]);

                // Descontar stock
                $producto->decrement('stock', $item['cantidad']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '¡Venta registrada exitosamente!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    // Mostrar detalle de una venta específica
    public function show($id)
    {
        $venta = Venta::with(['cliente', 'user', 'detalles.producto'])->findOrFail($id);

        // Si la petición viene por AJAX / Fetch, devolvemos JSON
        if (request()->expectsJson()) {
            return response()->json([
                'id' => $venta->id,
                'numero_comprobante' => $venta->numero_comprobante,
                'tipo_comprobante' => $venta->tipo_comprobante,
                'total' => $venta->total,
                'metodo_pago' => $venta->metodo_pago,
                'monto_recibido' => $venta->monto_recibido,
                'vuelto_entregado' => $venta->vuelto_entregado,
                'fecha_formateada' => $venta->created_at->format('Y-m-d H:i:s'),
                'cliente' => $venta->cliente,
                'detalles' => $venta->detalles
            ]);
        }

        return view('admin.ventas.show', compact('venta'));
    }

    // Anular o eliminar venta
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $venta = Venta::with('detalles')->findOrFail($id);

            // Devolver stock al inventario si se anula
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->increment('stock', $detalle->cantidad);
                }
            }

            $venta->delete();
            DB::commit();

            return redirect()->route('admin.ventas.index')->with([
                'mensaje' => 'Venta anulada correctamente',
                'icono' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.ventas.index')->with([
                'mensaje' => 'Error al anular la venta: ' . $e->getMessage(),
                'icono' => 'error'
            ]);
        }
    }

    // Exportar Excel (Pendiente de implementar según tu librería)
    public function pdf(Request $request)
    {
        $busqueda = $request->get('busqueda');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $ventas = Venta::with(['cliente', 'user', 'detalles.producto'])
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where('numero_comprobante', 'like', "%{$busqueda}%")
                             ->orWhereHas('cliente', function ($q) use ($busqueda) {
                                 $q->where('nombres', 'like', "%{$busqueda}%")
                                   ->orWhere('apellidos', 'like', "%{$busqueda}%");
                             });
            })
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                return $query->whereBetween('fecha_venta', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
            })
            ->when($fechaInicio && !$fechaFin, function ($query) use ($fechaInicio) {
                return $query->where('fecha_venta', '>=', $fechaInicio . ' 00:00:00');
            })
            ->when(!$fechaInicio && $fechaFin, function ($query) use ($fechaFin) {
                return $query->where('fecha_venta', '<=', $fechaFin . ' 23:59:59');
            })
            ->latest('fecha_venta')
            ->get();

        return view('admin.ventas.pdf', compact('ventas', 'busqueda', 'fechaInicio', 'fechaFin'));
    }

    // Exportar Reporte en Excel con filtro de fechas
    public function excel(Request $request)
    {
        $fileName = 'reporte_ventas_' . date('Y-m-d_H-i-s') . '.csv';
        $busqueda = $request->get('busqueda');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        $ventas = Venta::with(['cliente', 'user', 'detalles.producto'])
            ->when($busqueda, function ($query, $busqueda) {
                return $query->where('numero_comprobante', 'like', "%{$busqueda}%")
                             ->orWhereHas('cliente', function ($q) use ($busqueda) {
                                 $q->where('nombres', 'like', "%{$busqueda}%")
                                   ->orWhere('apellidos', 'like', "%{$busqueda}%");
                             });
            })
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                return $query->whereBetween('fecha_venta', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
            })
            ->when($fechaInicio && !$fechaFin, function ($query) use ($fechaInicio) {
                return $query->where('fecha_venta', '>=', $fechaInicio . ' 00:00:00');
            })
            ->when(!$fechaInicio && $fechaFin, function ($query) use ($fechaFin) {
                return $query->where('fecha_venta', '<=', $fechaFin . ' 23:59:59');
            })
            ->latest('fecha_venta')
            ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($ventas, $fechaInicio, $fechaFin) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para tildes

            // Cabeceras
            fputcsv($file, [
                'ID Venta', 'Nro Comprobante', 'Tipo Comprobante', 'Fecha Venta',
                'Cliente', 'Usuario (Atendido por)', 'Método Pago', 'Código Transacción',
                'Subtotal (Bs.)', 'Total (Bs.)', 'Monto Recibido (Bs.)', 'Vuelto (Bs.)', 'Estado',
                'Producto / Detalle', 'Cantidad Vendida', 'Precio Unitario (Bs.)', 'Subtotal Detalle (Bs.)'
            ], ';');

            $totalCantidadGeneral = 0;
            $totalMontoGeneral = 0;

            foreach ($ventas as $venta) {
                $clienteNombre = $venta->cliente ? $venta->cliente->nombres . ' ' . ($venta->cliente->apellidos ?? '') : 'Público General';
                $userName = $venta->user->name ?? 'N/A';

                if ($venta->detalles->count() > 0) {
                    foreach ($venta->detalles as $detalle) {
                        if ($venta->estado === 'Completado') {
                            $totalCantidadGeneral += $detalle->cantidad;
                            $totalMontoGeneral += $detalle->subtotal;
                        }

                        fputcsv($file, [
                            $venta->id,
                            $venta->numero_comprobante,
                            $venta->tipo_comprobante,
                            $venta->fecha_venta ?? $venta->created_at,
                            $clienteNombre,
                            $userName,
                            $venta->metodo_pago,
                            $venta->codigo_transaccion ?? 'N/A',
                            $venta->subtotal,
                            $venta->total,
                            $venta->monto_recibido,
                            $venta->vuelto_entregado,
                            $venta->estado,
                            $detalle->producto->nombre ?? $detalle->producto->name ?? 'Producto #' . $detalle->producto_id,
                            $detalle->cantidad,
                            $detalle->precio_venta,
                            $detalle->subtotal
                        ], ';');
                    }
                } else {
                    fputcsv($file, [
                        $venta->id, $venta->numero_comprobante, $venta->tipo_comprobante, $venta->fecha_venta ?? $venta->created_at,
                        $clienteNombre, $userName, $venta->metodo_pago, $venta->codigo_transaccion ?? 'N/A',
                        $venta->subtotal, $venta->total, $venta->monto_recibido, $venta->vuelto_entregado, $venta->estado,
                        'Sin detalles', 0, 0, 0
                    ], ';');
                }
            }

            fputcsv($file, [], ';');
            fputcsv($file, [
                '', '', '', '', '', '', '', '', '', '', '', '', '',
                'TOTAL GENERAL (COMPLETADAS):',
                $totalCantidadGeneral,
                '',
                $totalMontoGeneral
            ], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function getDetalles($id)
    {
        $venta = Venta::with(['cliente', 'detalles.producto'])->findOrFail($id);

        return response()->json([
            'id' => $venta->id,
            'numero_comprobante' => $venta->numero_comprobante,
            'fecha_formateada' => $venta->fecha_venta,
            'total' => $venta->total,
            'metodo_pago' => $venta->metodo_pago,
            'monto_recibido' => $venta->monto_recibido,
            'vuelto_entregado' => $venta->vuelto_entregado,
            'cliente' => $venta->cliente,
            'detalles' => $venta->detalles->map(function ($detalle) {
                return [
                    'id' => $detalle->id,
                    'producto' => $detalle->producto,
                    'cantidad' => $detalle->cantidad,
                    'precio_venta' => $detalle->precio_venta,
                    'subtotal' => $detalle->subtotal,
                ];
            })
        ]);
    }

    public function getTicket($id)
    {
        $venta = Venta::with(['detalles.producto', 'user', 'cliente'])->findOrFail($id);

        return response()->json([
            'venta' => $venta,
            'detalles' => $venta->detalles->map(function($detalle) {
                return [
                    'producto' => $detalle->producto ? $detalle->producto->name ?? $detalle->producto->nombre : 'Producto',
                    'cantidad' => $detalle->cantidad,
                    'precio_unitario' => $detalle->precio_venta,
                    'subtotal' => $detalle->subtotal
                ];
            })
        ]);
    }
}
