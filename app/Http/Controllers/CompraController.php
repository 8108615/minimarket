<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\CompraExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class CompraController extends Controller
{
    // Listado de compras
    public function index(Request $request)
    {
        $search = $request->input('search');

        $compras = Compra::with(['proveedor', 'user'])
            ->when($search, function ($query, $search) {
                return $query->where('id', 'like', "%{$search}%")
                    ->orWhereHas('proveedor', function ($q) use ($search) {
                        $q->where('nombre', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10);

        return view('admin.compras.index', compact('compras', 'search'));
    }

    // Vista para crear una nueva compra
    public function create()
    {
        $proveedores = Proveedor::where('estado', 'Activo')->get();
        $productos = Producto::where('estado', 'Activo')->get();
        $categorias = Categoria::where('estado', 'Activo')->get(); // Útil si crean productos al vuelo

        return view('admin.compras.create', compact('proveedores', 'productos', 'categorias'));
    }

    // Guardar la compra (con transacción de base de datos)
    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedors,id',
            'fecha_compra' => 'required|date',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_compra' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calcular el total general
            $totalGeneral = 0;
            foreach ($request->productos as $item) {
                $totalGeneral += $item['cantidad'] * $item['precio_compra'];
            }

            // 1. Crear la cabecera de la compra
            $compra = Compra::create([
                'proveedor_id' => $request->proveedor_id,
                'user_id' => Auth::id(),
                'fecha_compra' => $request->fecha_compra,
                'total' => $totalGeneral,
                'estado' => 'Completada',
            ]);

            // 2. Registrar los detalles y actualizar stock de productos
            foreach ($request->productos as $item) {
                $cantidad = $item['cantidad'];
                $precioCompra = $item['precio_compra'];
                $subtotal = $cantidad * $precioCompra;

                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $cantidad,
                    'precio_compra' => $precioCompra,
                    'subtotal' => $subtotal,
                ]);

                // Actualizar el stock y opcionalmente el precio de compra del producto
                $producto = Producto::findOrFail($item['producto_id']);
                $producto->stock += $cantidad;
                $producto->precio_compra = $precioCompra; // Actualiza al último precio de compra si se desea
                $producto->save();
            }

            DB::commit();

            return redirect()->route('admin.compras.index')->with('success', 'Compra registrada con éxito y stock actualizado.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al registrar la compra: ' . $e->getMessage()])->withInput();
        }
    }

    // Ver detalle de una compra
    public function show($id)
    {
        $compra = Compra::with(['user', 'proveedor', 'detalles.producto'])->findOrFail($id);
        
        return view('admin.compras.show', compact('compra'));
    }

    // Método extra para crear un producto "al vuelo" vía AJAX desde la pantalla de compras
    public function storeProductAjax(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'nullable|string|unique:productos,codigo',
            'categoria_id' => 'required|exists:categorias,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        try {
            $producto = Producto::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo ?? 'PROD-' . rand(1000, 9999),
                'categoria_id' => $request->categoria_id,
                'precio_compra' => $request->precio_compra,
                'precio_venta' => $request->precio_venta,
                'stock' => 0, // Inicia en 0, la compra sumará su cantidad
                'stock_minimo' => $request->stock_minimo ?? 5,
                'estado' => 'Activo',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Producto creado con éxito',
                'producto' => $producto
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el producto: ' . $e->getMessage()
            ], 422);
        }
    }

    // Anular una compra (revierta el stock)
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $compra = Compra::with('detalles.producto')->findOrFail($id);

            // Validar que no esté anulada previamente
            if ($compra->estado === 'Anulado') {
                return redirect()->route('admin.compras.index')
                    ->error('Esta compra ya se encuentra anulada.');
            }

            // 1. Revertir el stock de cada producto en el detalle
            foreach ($compra->detalles as $detalle) {
                if ($detalle->producto) {
                    // Restamos la cantidad comprada que se había sumado al inventario
                    $detalle->producto->stock -= $detalle->cantidad;
                    $detalle->producto->save();
                }
            }

            // 2. Cambiar el estado de la compra a Anulado
            $compra->estado = 'Anulado';
            $compra->save();

            DB::commit();

            return redirect()->route('admin.compras.index')
                ->with('success', 'Compra anulada y stock revertido correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.compras.index')
                ->with('error', 'Ocurrió un error al anular la compra: ' . $e->getMessage());
        }
    }

    public function excel()
    {
        return Excel::download(new CompraExport, 'compras_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function pdf()
    {
        $compras = Compra::with(['user', 'proveedor'])->latest()->get();
        
        $pdf = Pdf::loadView('admin.compras.pdf', compact('compras'));
        
        // Opcional: orientar en horizontal 'landscape' o dejar vertical 'portrait'
        return $pdf->download('reporte_compras_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
