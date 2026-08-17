<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

// Rutas para ajustes
Route::get('/admin/ajustes', [App\Http\Controllers\AjusteController::class, 'index'])->name('admin.ajustes.index')->middleware(['auth', 'can:Ver ajustes']);
Route::post('/admin/ajustes', [App\Http\Controllers\AjusteController::class, 'store'])->name('admin.ajustes.store')->middleware(['auth', 'can:Actualizar ajustes']);

// Rutas para roles
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('admin.roles.index')->middleware(['auth', 'can:Ver listado de roles']);
Route::get('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'create'])->name('admin.roles.create')->middleware(['auth', 'can:Ver formulario de creacion de rol']);
Route::post('/admin/roles/create', [App\Http\Controllers\RoleController::class, 'store'])->name('admin.roles.store')->middleware(['auth', 'can:Guardar rol']);
Route::get('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'show'])->name('admin.roles.show')->middleware(['auth', 'can:Ver datos del rol']);
Route::get('/admin/rol/{id}/edit', [App\Http\Controllers\RoleController::class, 'edit'])->name('admin.roles.edit')->middleware(['auth', 'can:Ver formulario de edicion del rol']);
Route::put('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('admin.roles.update')->middleware(['auth', 'can:Actualizar rol']);
Route::delete('/admin/rol/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware(['auth', 'can:Eliminar rol']);
Route::get('/admin/rol/{id}/permisos', [App\Http\Controllers\RoleController::class, 'permisos'])->name('admin.roles.permisos')->middleware(['auth', 'can:Ver formulario de permisos del rol']);
Route::put('/admin/rol/{id}/permisos', [App\Http\Controllers\RoleController::class, 'guardarPermisos'])->name('admin.roles.guardar_permisos')->middleware(['auth', 'can:Actualizar permisos del rol']);

// Rutas para usuarios
Route::get('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'index'])->name('admin.usuarios.index')->middleware(['auth', 'can:Ver listado de usuarios']);
Route::get('/admin/usuarios/create', [App\Http\Controllers\UsuarioController::class, 'create'])->name('admin.usuarios.create')->middleware(['auth', 'can:Ver formulario de creacion de usuario']);
Route::post('/admin/usuarios', [App\Http\Controllers\UsuarioController::class, 'store'])->name('admin.usuarios.store')->middleware(['auth', 'can:Guardar usuario']);
Route::get('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'show'])->name('admin.usuarios.show')->middleware(['auth', 'can:Ver datos del usuario']);
Route::get('/admin/usuario/{id}/edit', [App\Http\Controllers\UsuarioController::class, 'edit'])->name('admin.usuarios.edit')->middleware(['auth', 'can:Ver formulario de edicion del usuario']);
Route::put('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'update'])->name('admin.usuarios.update')->middleware(['auth', 'can:Actualizar usuario']);
Route::delete('/admin/usuario/{id}', [App\Http\Controllers\UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy')->middleware(['auth', 'can:Eliminar usuario']);

// Rutas para Categorías
Route::get('/admin/categorias', [App\Http\Controllers\CategoriaController::class, 'index'])->name('admin.categorias.index')->middleware(['auth', 'can:Ver listado de categorias']);
Route::get('/admin/categorias/create', [App\Http\Controllers\CategoriaController::class, 'create'])->name('admin.categorias.create')->middleware(['auth', 'can:Ver formulario de creacion de categoria']);
Route::post('/admin/categorias', [App\Http\Controllers\CategoriaController::class, 'store'])->name('admin.categorias.store')->middleware(['auth', 'can:Guardar categoria']);
Route::get('/admin/categoria/{id}', [App\Http\Controllers\CategoriaController::class, 'show'])->name('admin.categorias.show')->middleware(['auth', 'can:Ver datos de la categoria']);
Route::get('/admin/categoria/{id}/edit', [App\Http\Controllers\CategoriaController::class, 'edit'])->name('admin.categorias.edit')->middleware(['auth', 'can:Ver formulario de edicion de categoria']);
Route::put('/admin/categoria/{id}', [App\Http\Controllers\CategoriaController::class, 'update'])->name('admin.categorias.update')->middleware(['auth', 'can:Actualizar categoria']);
Route::delete('/admin/categoria/{id}', [App\Http\Controllers\CategoriaController::class, 'destroy'])->name('admin.categorias.destroy')->middleware(['auth', 'can:Eliminar categoria']);

// Rutas para Productos
Route::get('/admin/productos', [App\Http\Controllers\ProductoController::class, 'index'])->name('admin.productos.index')->middleware(['auth', 'can:Ver listado de productos']);
Route::get('/admin/productos/create', [App\Http\Controllers\ProductoController::class, 'create'])->name('admin.productos.create')->middleware(['auth', 'can:Ver formulario de creacion de producto']);
Route::post('/admin/productos', [App\Http\Controllers\ProductoController::class, 'store'])->name('admin.productos.store')->middleware(['auth', 'can:Guardar producto']);
Route::get('/admin/producto/{id}', [App\Http\Controllers\ProductoController::class, 'show'])->name('admin.productos.show')->middleware(['auth', 'can:Ver datos del producto']);
Route::get('/admin/producto/{id}/edit', [App\Http\Controllers\ProductoController::class, 'edit'])->name('admin.productos.edit')->middleware(['auth', 'can:Ver formulario de edicion de producto']);
Route::put('/admin/producto/{id}', [App\Http\Controllers\ProductoController::class, 'update'])->name('admin.productos.update')->middleware(['auth', 'can:Actualizar producto']);
Route::delete('/admin/producto/{id}', [App\Http\Controllers\ProductoController::class, 'destroy'])->name('admin.productos.destroy')->middleware(['auth', 'can:Eliminar producto']);
Route::post('/admin/productos/{id}/ajustar-stock', [App\Http\Controllers\ProductoController::class, 'ajustarStock'])->name('admin.productos.ajustar-stock')->middleware(['auth', 'can:Ajustar stock de producto']);
Route::get('admin/productos/exportar/excel', [App\Http\Controllers\ProductoController::class, 'exportarExcel'])->name('admin.productos.excel')->middleware(['auth', 'can:Exportar productos excel']);
Route::get('admin/productos/exportar/pdf', [App\Http\Controllers\ProductoController::class, 'exportarPdf'])->name('admin.productos.pdf')->middleware(['auth', 'can:Exportar productos pdf']);
Route::get('admin/productos/imprimir/barras', [App\Http\Controllers\ProductoController::class, 'imprimirCodigosBarras'])->name('admin.productos.barras')->middleware(['auth', 'can:Imprimir codigos de barras']);

// Rutas para Proveedores
Route::get('/admin/proveedores', [App\Http\Controllers\ProveedorController::class, 'index'])->name('admin.proveedores.index')->middleware(['auth', 'can:Ver listado de proveedores']);
Route::get('/admin/proveedores/create', [App\Http\Controllers\ProveedorController::class, 'create'])->name('admin.proveedores.create')->middleware(['auth', 'can:Ver formulario de creacion de proveedor']);
Route::post('/admin/proveedores', [App\Http\Controllers\ProveedorController::class, 'store'])->name('admin.proveedores.store')->middleware(['auth', 'can:Guardar proveedor']);
Route::get('/admin/proveedor/{id}', [App\Http\Controllers\ProveedorController::class, 'show'])->name('admin.proveedores.show')->middleware(['auth', 'can:Ver datos del proveedor']);
Route::get('/admin/proveedor/{id}/edit', [App\Http\Controllers\ProveedorController::class, 'edit'])->name('admin.proveedores.edit')->middleware(['auth', 'can:Ver formulario de edicion de proveedor']);
Route::put('/admin/proveedor/{id}', [App\Http\Controllers\ProveedorController::class, 'update'])->name('admin.proveedores.update')->middleware(['auth', 'can:Actualizar proveedor']);
Route::delete('/admin/proveedor/{id}', [App\Http\Controllers\ProveedorController::class, 'destroy'])->name('admin.proveedores.destroy')->middleware(['auth', 'can:Eliminar proveedor']);

// Rutas para Clientes
Route::get('/admin/clientes', [App\Http\Controllers\ClienteController::class, 'index'])->name('admin.clientes.index')->middleware(['auth', 'can:Ver listado de clientes']);
Route::get('/admin/clientes/create', [App\Http\Controllers\ClienteController::class, 'create'])->name('admin.clientes.create')->middleware(['auth', 'can:Ver formulario de creacion de clientes']);
Route::post('/admin/clientes', [App\Http\Controllers\ClienteController::class, 'store'])->name('admin.clientes.store')->middleware(['auth', 'can:Guardar cliente']);
Route::get('/admin/cliente/{id}', [App\Http\Controllers\ClienteController::class, 'show'])->name('admin.clientes.show')->middleware(['auth', 'can:Ver datos del cliente']);
Route::get('/admin/cliente/{id}/edit', [App\Http\Controllers\ClienteController::class, 'edit'])->name('admin.clientes.edit')->middleware(['auth', 'can:Ver formulario de edicion de cliente']);
Route::put('/admin/cliente/{id}', [App\Http\Controllers\ClienteController::class, 'update'])->name('admin.clientes.update')->middleware(['auth', 'can:Actualizar cliente']);
Route::delete('/admin/cliente/{id}', [App\Http\Controllers\ClienteController::class, 'destroy'])->name('admin.clientes.destroy')->middleware(['auth', 'can:Eliminar cliente']);

// Rutas para Compras
Route::get('/admin/compras', [App\Http\Controllers\CompraController::class, 'index'])->name('admin.compras.index')->middleware(['auth', 'can:Ver listado de compras']);
Route::get('/admin/compras/create', [App\Http\Controllers\CompraController::class, 'create'])->name('admin.compras.create')->middleware(['auth', 'can:Ver formulario de creacion de compra']);
Route::post('/admin/compras', [App\Http\Controllers\CompraController::class, 'store'])->name('admin.compras.store')->middleware(['auth', 'can:Guardar compra']);
Route::get('/admin/compras/excel', [App\Http\Controllers\CompraController::class, 'excel'])->name('admin.compras.excel')->middleware(['auth', 'can:Ver compras excel']);
Route::get('/admin/compras/pdf', [App\Http\Controllers\CompraController::class, 'pdf'])->name('admin.compras.pdf')->middleware(['auth', 'can:Ver compras pdf']);
Route::get('/admin/compra/{compra}', [App\Http\Controllers\CompraController::class, 'show'])->name('admin.compras.show')->middleware(['auth', 'can:Ver detalle de compra']);
Route::delete('/admin/compras/{compra}', [App\Http\Controllers\CompraController::class, 'destroy'])->name('admin.compras.destroy')->middleware(['auth', 'can:Eliminar compra']);
Route::post('/admin/compras/producto-ajax', [App\Http\Controllers\CompraController::class, 'storeProductAjax'])->name('admin.compras.producto-ajax')->middleware(['auth', 'can:Guardar producto ajax compra']);

// Rutas para Ventas
Route::get('/admin/ventas', [App\Http\Controllers\VentaController::class, 'index'])->name('admin.ventas.index')->middleware(['auth', 'can:Ver listado de ventas']);
Route::get('/admin/ventas/create', [App\Http\Controllers\VentaController::class, 'create'])->name('admin.ventas.create')->middleware(['auth', 'can:Ver formulario de creacion de venta']);
Route::post('/admin/ventas', [App\Http\Controllers\VentaController::class, 'store'])->name('admin.ventas.store')->middleware(['auth', 'can:Guardar venta']);
Route::get('/admin/ventas/excel', [App\Http\Controllers\VentaController::class, 'excel'])->name('admin.ventas.excel')->middleware(['auth', 'can:Ver ventas excel']);
Route::get('/admin/ventas/pdf', [App\Http\Controllers\VentaController::class, 'pdf'])->name('admin.ventas.pdf')->middleware(['auth', 'can:Ver ventas pdf']);
Route::get('/admin/ventas/{id}/detalles', [App\Http\Controllers\VentaController::class, 'getDetalles'])->name('admin.ventas.getDetalles')->middleware(['auth', 'can:Ver detalles de venta']);
Route::get('/admin/ventas/{id}/ticket', [App\Http\Controllers\VentaController::class, 'getTicket'])->name('admin.ventas.getTicket')->middleware(['auth', 'can:Ver ticket de venta']);
Route::get('/admin/ventas/{id}', [App\Http\Controllers\VentaController::class, 'show'])->name('admin.ventas.show')->middleware(['auth', 'can:Ver detalle de venta individual']);
Route::delete('/admin/ventas/{id}', [App\Http\Controllers\VentaController::class, 'destroy'])->name('admin.ventas.destroy')->middleware(['auth', 'can:Eliminar venta']);

// Rutas para Cajas
Route::get('/admin/cajas', [App\Http\Controllers\CajaController::class, 'index'])->name('admin.cajas.index')->middleware(['auth', 'can:Ver listado de cajas']);
Route::get('/admin/cajas/create', [App\Http\Controllers\CajaController::class, 'create'])->name('admin.cajas.create')->middleware(['auth', 'can:Ver formulario de creacion de caja']);
Route::post('/admin/cajas', [App\Http\Controllers\CajaController::class, 'store'])->name('admin.cajas.store')->middleware(['auth', 'can:Guardar caja']);
Route::get('/admin/caja/{id}', [App\Http\Controllers\CajaController::class, 'show'])->name('admin.cajas.show')->middleware(['auth', 'can:Ver datos de la caja']);
Route::get('/admin/caja/{id}/edit', [App\Http\Controllers\CajaController::class, 'edit'])->name('admin.cajas.edit')->middleware(['auth', 'can:Ver formulario de edicion de caja']);
Route::put('/admin/caja/{id}', [App\Http\Controllers\CajaController::class, 'update'])->name('admin.cajas.update')->middleware(['auth', 'can:Actualizar caja']);
Route::delete('/admin/caja/{id}', [App\Http\Controllers\CajaController::class, 'destroy'])->name('admin.cajas.destroy')->middleware(['auth', 'can:Eliminar caja']);
// Rutas para gestión de Caja (Apertura/Cierre)
Route::post('/admin/cajas/abrir', [App\Http\Controllers\CajaController::class, 'abrirCaja'])->name('admin.cajas.abrir')->middleware(['auth', 'can:Abrir caja']);
Route::get('cajas/{id}/pdf', [App\Http\Controllers\CajaController::class, 'pdf'])->name('admin.cajas.pdf')->middleware(['auth', 'can:Ver reporte caja pdf']);
Route::post('/admin/cajas/cerrar/{id}', [App\Http\Controllers\CajaController::class, 'cerrarCaja'])->name('admin.cajas.cerrar')->middleware(['auth', 'can:Cerrar caja']);

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('admin.dashboard')->middleware(['auth', 'can:Ver dashboard']);

require __DIR__.'/settings.php';
