<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Caja;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Tarjetas Superiores (KPIs)
        $ventasHoy = Venta::whereDate('fecha_venta', today())->sum('total');
        $ventasMes = Venta::whereMonth('fecha_venta', now()->month)
                          ->whereYear('fecha_venta', now()->year)
                          ->sum('total');
        $cantidadVentasMes = Venta::whereMonth('fecha_venta', now()->month)
                                  ->whereYear('fecha_venta', now()->year)
                                  ->count();
        $totalClientes = Cliente::count();
        $totalProductos = Producto::count();

        // 2. Productos con stock bajo (ej. stock menor o igual a 5)
        $productosBajosStock = Producto::where('stock', '<=', 5)->take(5)->get();

        // 3. Últimas ventas realizadas
        $ultimasVentas = Venta::with('cliente')->latest()->take(5)->get();

        // 4. Productos más vendidos (agrupando por producto en los detalles de venta)
        $productosMasVendidos = \DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->select('productos.nombre', \DB::raw('SUM(detalle_ventas.cantidad) as total_cantidad'), \DB::raw('SUM(detalle_ventas.subtotal) as total_ingreso'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total_cantidad')
            ->take(5)
            ->get();

        // 5. Mejores Clientes (según cantidad de compras o total gastado)
        $mejoresClientes = Cliente::withCount('ventas')
            ->withSum('ventas', 'total')
            ->orderByDesc('ventas_sum_total')
            ->take(5)
            ->get();

        // Leer símbolo de moneda
        $simboloMoneda = 'Bs.';
        $pathDivisas = public_path('divisas.json');
        if (file_exists($pathDivisas)) {
            $divisasData = json_decode(file_get_contents($pathDivisas), true);
            $simboloMoneda = $divisasData['simbolo'] ?? ($divisasData[0]['simbolo'] ?? 'Bs.');
        }

        return view('admin.dashboard', compact(
            'ventasHoy', 
            'ventasMes', 
            'cantidadVentasMes', 
            'totalClientes', 
            'totalProductos', 
            'productosBajosStock', 
            'ultimasVentas', 
            'productosMasVendidos', 
            'mejoresClientes',
            'simboloMoneda'
        ));
    }
}