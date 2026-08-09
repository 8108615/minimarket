<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->get('buscar');

        $proveedores = Proveedor::when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'LIKE', "%{$buscar}%")
                             ->orWhere('nit', 'LIKE', "%{$buscar}%")
                             ->orWhere('telefono', 'LIKE', "%{$buscar}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.proveedores.index', compact('proveedores', 'buscar'));
    }

    public function create()
    {
        return view('admin.proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('admin.proveedores.index')
            ->with('mensaje', 'Proveedor registrado exitosamente.')
            ->with('icono', 'success');
    }

    public function show($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('admin.proveedores.show', compact('proveedor'));
    }

    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('admin.proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'estado' => 'required|in:Activo,Inactivo',
        ]);

        $proveedor->update($request->all());

        return redirect()->route('admin.proveedores.index')
            ->with('mensaje', 'Proveedor actualizado exitosamente.')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();

        return redirect()->route('admin.proveedores.index')
            ->with('mensaje', 'Proveedor eliminado exitosamente.')
            ->with('icono', 'success');
    }
}
