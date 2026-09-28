<?php

namespace Database\Seeders;

use App\Models\Ajuste;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Usuario administrador (evita duplicados buscando por email)
        $user = User::firstOrCreate(
            ['email' => 'erick@gmail.com'],
            [
                'name' => 'Erick Fernando Morales Gil',
                'foto_perfil' => null,
                'estado' => 'Activo',
                'password' => bcrypt('12345678'),
            ]
        );
        
        // Asignar el rol si no lo tiene ya asignado
        if (!$user->hasRole('SUPER ADMIN')) {
            $user->assignRole('SUPER ADMIN');
        }

        // Ajustes del sistema (busca por ID o nombre para no duplicar)
        Ajuste::firstOrCreate(
            ['id' => 1],
            [
                'nombre' => 'MINIMARKET',
                'descripcion' => 'Sistema de Ventas',
                'direccion' => 'Av Cumavi',
                'telefono' => '76658532',
                'email' => 'minimarket@gmail.com',
                'divisa' => 'BOB',
                'logo' => null,
                'web' => 'https://www.minimarket.com',
            ]
        );

        // Categorías (busca por nombre para evitar duplicados)
        $categorias = [
            ['nombre' => 'GASEOSAS', 'descripcion' => 'Todas las Bebidas gaseosas', 'estado' => 'Activo'],
            ['nombre' => 'AZUCAR', 'descripcion' => 'Todo Tipos Azucar', 'estado' => 'Activo'],
            ['nombre' => 'ARROZ', 'descripcion' => 'Todo Tipos Arroz', 'estado' => 'Activo'],
            ['nombre' => 'ACEITES', 'descripcion' => 'Todo Tipos Aceites', 'estado' => 'Activo'],
        ];

        foreach ($categorias as $cat) {
            Categoria::firstOrCreate(['nombre' => $cat['nombre']], $cat);
        }

        // Proveedores (busca por NIT)
        $proveedores = [
            ['nombre' => 'Mario - Coca Cola', 'nit' => '1000001', 'telefono' => '79999991', 'email' => 'mario@gmail.com', 'direccion' => 'Av. Paragua', 'estado' => 'Activo'],
            ['nombre' => 'Rodolfo - Arroz Carolina', 'nit' => '1000002', 'telefono' => '79999992', 'email' => 'rodolfo@gmail.com', 'direccion' => 'Av. Banzer', 'estado' => 'Activo'],
            ['nombre' => 'Limber - Azucar Guabira', 'nit' => '1000003', 'telefono' => '79999993', 'email' => 'limber@gmail.com', 'direccion' => 'Av. Guabira', 'estado' => 'Activo'],
        ];

        foreach ($proveedores as $prov) {
            Proveedor::firstOrCreate(['nit' => $prov['nit']], $prov);
        }

        // Clientes (busca por CI)
        $clientes = [
            ['nombres' => 'Roger Perez', 'ci' => '8111111', 'telefono' => '75555551', 'email' => 'roger@gmail.com', 'direccion' => 'Av. Principal', 'estado' => 'Activo'],
            ['nombres' => 'Marcelo Gonzales', 'ci' => '8111112', 'telefono' => '75555552', 'email' => 'marcelo@gmail.com', 'direccion' => 'Av. Secundaria', 'estado' => 'Activo'],
            ['nombres' => 'Victor Hugo', 'ci' => '8111113', 'telefono' => '75555553', 'email' => 'victor@gmail.com', 'direccion' => 'Av. Terciaria', 'estado' => 'Activo'],
        ];

        foreach ($clientes as $cli) {
            Cliente::firstOrCreate(['ci' => $cli['ci']], $cli);
        }
    }
}
