<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Roles principales usando firstOrCreate
        $super_admin = Role::firstOrCreate(['name' => 'SUPER ADMIN', 'guard_name' => 'web']);
        $administrador = Role::firstOrCreate(['name' => 'ADMINISTRADOR', 'guard_name' => 'web']);
        $cajero = Role::firstOrCreate(['name' => 'CAJERO', 'guard_name' => 'web']);

        // 2. Definición de Permisos por Módulo
        $permisos = [
            // Ajustes
            'Ver ajustes', 'Actualizar ajustes',

            // Roles
            'Ver listado de roles', 'Ver formulario de creacion de rol', 'Guardar rol',
            'Ver datos del rol', 'Ver formulario de edicion del rol', 'Actualizar rol',
            'Eliminar rol', 'Ver formulario de permisos del rol', 'Actualizar permisos del rol',

            // Usuarios
            'Ver listado de usuarios', 'Ver formulario de creacion de usuario', 'Guardar usuario',
            'Ver datos del usuario', 'Ver formulario de edicion del usuario', 'Actualizar usuario',
            'Eliminar usuario',

            // Categorías
            'Ver listado de categorias', 'Ver formulario de creacion de categoria', 'Guardar categoria',
            'Ver datos de la categoria', 'Ver formulario de edicion de categoria', 'Actualizar categoria',
            'Eliminar categoria',

            // Productos
            'Ver listado de productos', 'Ver formulario de creacion de producto', 'Guardar producto',
            'Ver datos del producto', 'Ver formulario de edicion de producto', 'Actualizar producto',
            'Eliminar producto', 'Ajustar stock de producto', 'Exportar productos excel',
            'Exportar productos pdf', 'Imprimir codigos de barras',

            // Proveedores
            'Ver listado de proveedores', 'Ver formulario de creacion de proveedor', 'Guardar proveedor',
            'Ver datos del proveedor', 'Ver formulario de edicion de proveedor', 'Actualizar proveedor',
            'Eliminar proveedor',

            // Clientes
            'Ver listado de clientes', 'Ver formulario de creacion de clientes', 'Guardar cliente',
            'Ver datos del cliente', 'Ver formulario de edicion de cliente', 'Actualizar cliente',
            'Eliminar cliente',

            // Compras
            'Ver listado de compras', 'Ver formulario de creacion de compra', 'Guardar compra',
            'Ver compras excel', 'Ver compras pdf', 'Ver detalle de compra', 'Eliminar compra',
            'Guardar producto ajax compra',

            // Ventas
            'Ver listado de ventas', 'Ver formulario de creacion de venta', 'Guardar venta',
            'Ver ventas excel', 'Ver ventas pdf', 'Ver detalles de venta', 'Ver ticket de venta',
            'Ver detalle de venta individual', 'Eliminar venta',

            // Cajas
            'Ver listado de cajas', 'Ver formulario de creacion de caja', 'Guardar caja',
            'Ver datos de la caja', 'Ver formulario de edicion de caja', 'Actualizar caja',
            'Eliminar caja', 'Abrir caja', 'Ver reporte caja pdf', 'Cerrar caja',
        ];

        // Crear los permisos de forma segura y asignárselos al Super Admin
        foreach ($permisos as $permiso) {
            $p = Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
            
            // Sincronizar para evitar duplicidad en la relación con el rol
            if (!$p->hasRole($super_admin)) {
                $p->assignRole($super_admin);
            }
        }
    }
}
