<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Ajuste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductosExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductoController extends Controller
{
    /**
     * Obtiene el símbolo de la moneda configurado en los ajustes.
     */
    private function obtenerSimboloDivisa()
    {
        $ajuste = Ajuste::first();
        $simboloDivisa = 'Bs'; // Valor por defecto si no encuentra nada

        if ($ajuste && $ajuste->divisa) {
            $path = public_path('divisas.json');
            if (file_exists($path)) {
                $divisas = json_decode(file_get_contents($path), true);
                if (isset($divisas[$ajuste->divisa])) {
                    $simboloDivisa = $divisas[$ajuste->divisa]['symbol'];
                }
            }
        }

        return $simboloDivisa;
    }

    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $productos = Producto::with('categoria')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', "%{$buscar}%")
                            ->orWhere('codigo', 'LIKE', "%{$buscar}%");
            })
            ->latest()
            ->paginate(10);

        // NUEVO: Obtenemos los productos cuyo stock es menor o igual al mínimo
        $productosCriticos = Producto::whereColumn('stock', '<=', 'stock_minimo')->get();

        $simboloDivisa = $this->obtenerSimboloDivisa();

        // Pasamos $productosCriticos a la vista
        return view('admin.productos.index', compact('productos', 'buscar', 'simboloDivisa', 'productosCriticos'));
    }

    public function create()
    {
        $categorias = Categoria::where('estado', 'Activo')->get();
        $simboloDivisa = $this->obtenerSimboloDivisa();

        return view('admin.productos.create', compact('categorias', 'simboloDivisa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:productos,codigo',
            'categoria_id' => 'required|exists:categorias,id',
            'precio_venta' => 'required|numeric|min:0',
            'precio_compra' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'estado' => 'required|in:Activo,Inactivo',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($data);

        return redirect()->route('admin.productos.index')
            ->with('mensaje', 'Producto registrado exitosamente.')
            ->with('icono', 'success');
    }

    public function show($id)
    {
        $producto = Producto::with('categoria')->findOrFail($id);
        $simboloDivisa = $this->obtenerSimboloDivisa();

        return view('admin.productos.show', compact('producto', 'simboloDivisa'));
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::where('estado', 'Activo')->get();
        $simboloDivisa = $this->obtenerSimboloDivisa();

        return view('admin.productos.edit', compact('producto', 'categorias', 'simboloDivisa'));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:productos,codigo,' . $producto->id,
            'categoria_id' => 'required|exists:categorias,id',
            'precio_venta' => 'required|numeric|min:0',
            'precio_compra' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'estado' => 'required|in:Activo,Inactivo',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('admin.productos.index')
            ->with('mensaje', 'Producto actualizado exitosamente.')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
                ->with('mensaje', 'Producto eliminado exitosamente.')
                ->with('icono', 'success');
    }

    public function ajustarStock(Request $request, $id)
    {
        $request->validate([
            'tipo_movimiento' => 'required|in:entrada,salida',
            'cantidad' => 'required|integer|min:1',
        ]);

        $producto = Producto::findOrFail($id);

        if ($request->tipo_movimiento === 'entrada') {
            $producto->stock += $request->cantidad;
            $mensaje = "Se agregaron {$request->cantidad} unidades correctamente.";
        } else {
            if ($producto->stock < $request->cantidad) {
                return back()->with('error', 'Stock insuficiente para realizar la salida.');
            }
            $producto->stock -= $request->cantidad;
            $mensaje = "Se retiraron {$request->cantidad} unidades correctamente.";
        }

        $producto->save();

        // Verificamos si quedó en stock crítico
        if ($producto->stock <= $producto->stock_minimo) {
            return back()->with('mensaje', '¡Atención! ' . $mensaje . ' El stock de este producto ha llegado al límite mínimo.')
                        ->with('icono', 'warning'); // Esto será capturado por tu layout
        }

        return back()->with('mensaje', $mensaje)
                    ->with('icono', 'success');
    }

    public function exportarExcel()
    {
        return Excel::download(new ProductosExport, 'productos_' . date('Y-m-d') . '.xlsx');
    }

    public function exportarPdf()
    {
        $productos = Producto::with('categoria')->get();

        // Configuramos el papel en tamaño carta ('a4') y en horizontal ('landscape')
        $pdf = Pdf::loadView('admin.productos.pdf', compact('productos'))
                ->setPaper('a4', 'landscape');

        return $pdf->download('productos_' . date('Y-m-d') . '.pdf');
    }

    public function imprimirCodigosBarras()
    {
        // Obtenemos todos los productos activos o todos en general
        $productos = Producto::all();

        return view('admin.productos.barras', compact('productos'));
    }
}
