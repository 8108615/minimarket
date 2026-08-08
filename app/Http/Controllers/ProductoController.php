<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Ajuste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $simboloDivisa = $this->obtenerSimboloDivisa(); // Obtenemos el símbolo

        return view('admin.productos.index', compact('productos', 'buscar', 'simboloDivisa'));
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
        return view('admin.productos.show', compact('producto'));
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
}
