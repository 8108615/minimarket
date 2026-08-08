<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $roles = Role::where('name', 'like', '%' . $buscar . '%')
                    ->paginate(10)
                    ->withQueryString();

        return view('admin.roles.index', compact('roles', 'buscar'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $rol = new Role();
        $rol->name = $request->name;
        $rol->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol guardado correctamente')
            ->with('icono', 'success');
    }

    public function show(string $id)
    {
        $rol = Role::find($id);
        return view('admin.roles.show', compact('rol'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rol = Role::find($id);
        return view('admin.roles.edit', compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $rol = Role::find($id);
        $rol->name = $request->name;
        $rol->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rol = Role::find($id);
        $rol->delete();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Rol eliminado correctamente')
            ->with('icono', 'success');
    }

    /**public function permisos(string $id)
    {
        $rol = Role::find($id);

        // Obtenemos todos los permisos y los agrupamos por su módulo (ej: usuarios, roles, empleados)
        // Tomando como base el formato de nombre: admin.modulo.accion
        $permisos = Permission::all()->groupBy(function($permission) {
            $parts = explode('.', $permission->name);
            // Si tiene al menos 3 partes (ej. admin.usuarios.index), agrupa por la segunda parte.
            // Si no, los agrupa bajo 'general' u otro nombre.
            return isset($parts[1]) ? ucfirst($parts[1]) : 'General';
        });

        return view('admin.roles.permisos', compact('rol', 'permisos'));
    }


     * Guardar los permisos asignados al rol.

    public function guardarPermisos(Request $request, string $id)
    {
        $rol = Role::find($id);

        // Sincroniza los permisos seleccionados (espera un array de nombres de permisos)
        $rol->syncPermissions($request->input('permisos', []));

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Permisos asignados correctamente')
            ->with('icono', 'success');
    }*/
}
