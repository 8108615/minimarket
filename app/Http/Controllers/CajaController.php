<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CajaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $cajas = Caja::with('user')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('estado', 'like', "%{$buscar}%")
                            ->orWhereHas('user', function ($q) use ($buscar) {
                                $q->where('name', 'like', "%{$buscar}%");
                            });
            })
            ->latest()
            ->paginate(10);

        // Leer el símbolo de la moneda desde public/divisas.json
        $simboloMoneda = 'Bs.';
        $pathDivisas = public_path('divisas.json');
        if (file_exists($pathDivisas)) {
            $divisasData = json_decode(file_get_contents($pathDivisas), true);
            $simboloMoneda = $divisasData['simbolo'] ?? ($divisasData[0]['simbolo'] ?? 'Bs.');
        }

        return view('admin.cajas.index', compact('cajas', 'simboloMoneda'));
    }

    public function create()
    {
        return view('admin.cajas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'saldo_inicial' => 'required|numeric|min:0',
        ]);

        try {
            // Verificar si el usuario ya tiene una caja abierta
            $cajaAbierta = Caja::where('user_id', Auth::id())
                               ->where('estado', 'abierto')
                               ->exists();

            if ($cajaAbierta) {
                return redirect()->back()
                    ->with(['mensaje' => 'Ya tienes una caja abierta. Debes cerrarla antes de abrir una nueva.', 'icono' => 'error']);
            }

            Caja::create([
                'user_id' => Auth::id(),
                'saldo_inicial' => $request->saldo_inicial,
                'fecha_apertura' => now(),
                'estado' => 'abierto',
            ]);

            return redirect()->route('admin.cajas.index')
                ->with(['mensaje' => 'Caja abierta correctamente', 'icono' => 'success']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['mensaje' => 'Error al abrir la caja: ' . $e->getMessage(), 'icono' => 'error']);
        }
    }

    public function show($id)
    {
        $caja = Caja::findOrFail($id);

        // Leer el símbolo de la moneda desde public/divisas.json
        $simboloMoneda = 'Bs.';
        $pathDivisas = public_path('divisas.json');
        if (file_exists($pathDivisas)) {
            $divisasData = json_decode(file_get_contents($pathDivisas), true);
            $simboloMoneda = $divisasData['simbolo'] ?? ($divisasData[0]['simbolo'] ?? 'Bs.');
        }

        return view('admin.cajas.show', compact('caja', 'simboloMoneda'));
    }

    public function abrirCaja(Request $request)
    {
        // Lógica específica si decides usar un modal o ruta dedicada para abrir
        return $this->store($request);
    }

    public function cerrarCaja(Request $request, $id)
    {
        try {
            $caja = Caja::findOrFail($id);

            // Verificar que la caja esté abierta
            if ($caja->estado !== 'abierto') {
                return redirect()->back()
                    ->with(['mensaje' => 'Esta caja ya se encuentra cerrada.', 'icono' => 'error']);
            }

            // 1. Sumar ventas que tengan explícitamente el caja_id
            $ventasPorId = Venta::where('caja_id', $caja->id)->sum('total');

            // 2. Por seguridad, sumar también las ventas del usuario hechas entre la fecha de apertura y ahora (si no se les asignó caja_id)
            $ventasPorFecha = Venta::where('user_id', $caja->user_id)
                ->whereNull('caja_id')
                ->whereBetween('fecha_venta', [$caja->fecha_apertura, now()])
                ->sum('total');

            // Total de ventas real es la combinación de ambas
            $totalVentas = $ventasPorId + $ventasPorFecha;

            // Saldo final = Saldo inicial (ej. 100) + Total de ventas (ej. 77) = 177
            $saldoFinal = $caja->saldo_inicial + $totalVentas;

            // Actualizar la caja con los datos calculados
            $caja->update([
                'total_ventas' => $totalVentas,
                'saldo_final' => $saldoFinal,
                'fecha_cierre' => now(),
                'estado' => 'cerrado',
            ]);

            return redirect()->route('admin.cajas.index')
                ->with(['mensaje' => 'Caja cerrada correctamente. Total vendido: ' . number_format($totalVentas, 2) . ' | Saldo final: ' . number_format($saldoFinal, 2), 'icono' => 'success']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['mensaje' => 'Error al cerrar la caja: ' . $e->getMessage(), 'icono' => 'error']);
        }
    }

    public function destroy($id)
    {
        try {
            $caja = Caja::findOrFail($id);
            $caja->delete();

            return redirect()->route('admin.cajas.index')
                ->with(['mensaje' => 'Caja eliminada correctamente', 'icono' => 'success']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with(['mensaje' => 'No se puede eliminar la caja: ' . $e->getMessage(), 'icono' => 'error']);
        }
    }
}
