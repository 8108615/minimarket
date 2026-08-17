<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Caja;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // 2. Datos para la Gráfica de Líneas (Últimos 7 días de ventas)
        $ventasUltimosDias = [];
        $fechasUltimosDias = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $fechasUltimosDias[] = $fecha->format('d M');
            $ventasUltimosDias[] = Venta::whereDate('fecha_venta', $fecha)->sum('total');
        }

        // 3. Datos para la Gráfica de Dona y Desglose (Ventas por Método de Pago)
        $ventasPorMetodoRaw = Venta::select('metodo_pago', \DB::raw('SUM(total) as total'))
            ->groupBy('metodo_pago')
            ->pluck('total', 'metodo_pago')
            ->toArray();

        $totalGeneralMetodos = array_sum($ventasPorMetodoRaw);

        $metodosLabels = [];
        $metodosData = [];
        $metodosDetalle = [];

        // Colores estilizados para cada método de pago
        $coloresDisponibles = ['#06B6D4', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899'];
        $i = 0;

        foreach ($ventasPorMetodoRaw as $metodo => $monto) {
            $nombreMetodo = $metodo ? ucfirst($metodo) : 'Efectivo';
            $porcentaje = $totalGeneralMetodos > 0 ? round(($monto / $totalGeneralMetodos) * 100, 1) : 0;
            $color = $coloresDisponibles[$i % count($coloresDisponibles)];

            $metodosLabels[] = $nombreMetodo;
            $metodosData[] = $monto;

            $metodosDetalle[] = [
                'nombre' => $nombreMetodo,
                'monto' => $monto,
                'porcentaje' => $porcentaje,
                'color' => $color
            ];
            $i++;
        }

        // 4. Productos con stock bajo
        $productosBajosStock = Producto::where('stock', '<=', 5)->take(5)->get();

        // 5. Últimas ventas realizadas
        $ultimasVentas = Venta::with('cliente')->latest()->take(5)->get();

        // 6. Productos más vendidos
        $productosMasVendidos = \DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->select(
                'productos.nombre',
                'productos.imagen',
                \DB::raw('SUM(detalle_ventas.cantidad) as total_cantidad'),
                \DB::raw('SUM(detalle_ventas.subtotal) as total_ingreso')
            )
            ->groupBy('productos.id', 'productos.nombre', 'productos.imagen')
            ->orderByDesc('total_cantidad')
            ->take(5)
            ->get();

        // 7. Mejores Clientes
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
            'simboloMoneda',
            'fechasUltimosDias',
            'ventasUltimosDias',
            'metodosLabels',
            'metodosData',
            'metodosDetalle',
            'totalGeneralMetodos'
        ));
    }
}
