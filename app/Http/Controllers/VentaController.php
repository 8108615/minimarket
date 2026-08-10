<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $metodoPago = $request->input('metodo_pago');
        $estado = $request->input('estado');

        $ventas = Venta::with(['cliente', 'user', 'detalles.producto'])
            ->when($search, function ($query, $search) {
                $query->where('id', 'like', "%{$search}%")
                    ->orWhereHas('cliente', fn($q) => $q->where('nombre', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->when($metodoPago, fn($query, $mp) => $query->where('metodo_pago', $mp))
            ->when($estado, fn($query, $est) => $query->where('estado', $est))
            ->latest('fecha_venta')
            ->paginate(10)
            ->withQueryString();

        return view('admin.ventas.index', compact('ventas'));
    }
    public function create()
    {
        $clientes = Cliente::where('estado', 'Activo')->get();
        $productos = Producto::where('stock', '>', 0)->get(); // Solo productos con stock disponible

        return view('admin.ventas.create', compact('clientes', 'productos'));
    }
    public function store(Request $request)
    {
        // 1. Validar los datos recibidos
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'metodo_pago' => 'required|in:Efectivo,QR,Tarjeta',
            'productos' => 'required|array|min:1', // Esperamos un array de productos
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_venta' => 'required|numeric|min:0',
        ]);

        // 2. Usar transacción para asegurar integridad
        return DB::transaction(function () use ($request) {

            // Crear la cabecera de la venta
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => Auth::id(),
                'total' => collect($request->productos)->sum(fn($p) => $p['cantidad'] * $p['precio_venta']),
                'metodo_pago' => $request->metodo_pago,
                'estado' => 'Completado',
                'fecha_venta' => now(),
            ]);

            // 3. Procesar detalles y descontar stock
            foreach ($request->productos as $item) {
                $producto = Producto::find($item['id']);

                // Crear detalle
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_venta' => $item['precio_venta'],
                    'subtotal' => $item['cantidad'] * $item['precio_venta'],
                ]);

                // Descontar stock
                $producto->decrement('stock', $item['cantidad']);
            }

            return response()->json(['message' => 'Venta realizada con éxito', 'venta_id' => $venta->id], 201);
        });
    }


}
