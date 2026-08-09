<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');
        $clientes = Cliente::query()
            ->when($buscar, function ($query, $buscar) {
                $query->where('nombres', 'like', "%{$buscar}%")
                      ->orWhere('ci', 'like', "%{$buscar}%")
                      ->orWhere('telefono', 'like', "%{$buscar}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'ci' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'required',
        ]);

        Cliente::create($request->all());

        return redirect()->route('admin.clientes.index')
                ->with('mensaje', 'Cliente registrado exitosamente.')
                ->with('icono', 'success');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.show', compact('cliente'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'ci' => 'nullable|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:255',
            'estado' => 'required',
        ]);

        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());

        return redirect()->route('admin.clientes.index')
                    ->with('mensaje', 'Cliente actualizado exitosamente.')
                    ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('admin.clientes.index')
            ->with('mensaje', 'Cliente eliminado exitosamente.')
            ->with('icono', 'success');
    }
}
