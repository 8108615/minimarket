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
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
        ]);

        User::create([
            'name' => 'Erick Fernando Morales Gil',
            'email' => 'erick@gmail.com',
            'foto_perfil' => null,
            'estado' => 'Activo',
            'password' => bcrypt('12345678'),
        ])->assignRole('SUPER ADMIN');

        Ajuste::create([
            'nombre' => 'MINIMARKET',
            'descripcion' => 'Sistema de Ventas',
            'direccion' => 'Av Cumavi',
            'telefono' => '76658532',
            'email' => 'minimarket@gmail.com',
            'divisa' => 'BOB',
            'logo' => null,
            'web' => 'https://www.minimarket.com',
        ]);
        Categoria::create([
            'nombre' => 'GASEOSAS',
            'descripcion' => 'Todas las Bebidas gaseosas',
            'estado' => 'Activo',
        ]);
        Categoria::create([
            'nombre' => 'AZUCAR',
            'descripcion' => 'Todo Tipos Azucar',
            'estado' => 'Activo',
        ]);
        Categoria::create([
            'nombre' => 'ARROZ',
            'descripcion' => 'Todo Tipos Arroz',
            'estado' => 'Activo',
        ]);
        Categoria::create([
            'nombre' => 'ACEITES',
            'descripcion' => 'Todo Tipos Aceites',
            'estado' => 'Activo',
        ]);
        Proveedor::create([
            'nombre' => 'Mario - Coca Cola',
            'nit' => '1000001',
            'telefono' => '79999991',
            'email' => 'mario@gmail.com',
            'direccion' => 'Av. Paragua',
            'estado' => 'Activo',
        ]);
        Proveedor::create([
            'nombre' => 'Rodolfo - Arroz Carolina',
            'nit' => '1000002',
            'telefono' => '79999992',
            'email' => 'rodolfo@gmail.com',
            'direccion' => 'Av. Banzer',
            'estado' => 'Activo',
        ]);
        Proveedor::create([
            'nombre' => 'Limber - Azucar Guabira',
            'nit' => '1000003',
            'telefono' => '79999993',
            'email' => 'limber@gmail.com',
            'direccion' => 'Av. Guabira',
            'estado' => 'Activo',
        ]);
        Cliente::create([
            'nombres' => 'Roger Perez',
            'ci' => '8111111',
            'telefono' => '75555551',
            'email' => 'roger@gmail.com',
            'direccion' => 'Av. Principal',
            'estado' => 'Activo',
        ]);
        Cliente::create([
            'nombres' => 'Marcelo Gonzales',
            'ci' => '8111112',
            'telefono' => '75555552',
            'email' => 'marcelo@gmail.com',
            'direccion' => 'Av. Secundaria',
            'estado' => 'Activo',
        ]);
        Cliente::create([
            'nombres' => 'Victor Hugo',
            'ci' => '8111113',
            'telefono' => '75555553',
            'email' => 'victor@gmail.com',
            'direccion' => 'Av. Terciaria',
            'estado' => 'Activo',
        ]);

    }
}
