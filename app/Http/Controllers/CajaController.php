<?php

namespace App\Http\Controllers;

use App\Models\Caja;
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

        return view('admin.cajas.index', compact('cajas'));
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
        return view('admin.cajas.show', compact('caja'));
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

            // Aquí puedes calcular el saldo final basado en las ventas si lo deseas
            $caja->update([
                'fecha_cierre' => now(),
                'estado' => 'cerrado',
            ]);

            return redirect()->route('admin.cajas.index')
                ->with(['mensaje' => 'Caja cerrada correctamente', 'icono' => 'success']);
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
