<?php

namespace Database\Seeders;

use App\Models\Supermercado;
use Illuminate\Database\Seeder;

class SupermercadoSeeder extends Seeder{

    public function run(): void{
        Supermercado::create([
            'Nombre' => 'Supermercado A',
            'NIT' => '1234567890',
            'Direccion' => 'Calle 123, Ciudad A',
            'Logo' => 'supermercado_a.png',
            'Latitud' => '123.456',
            'Longitud' => '456.789',
            'ID_ciudad' => 1,
        ]);

        Supermercado::create([
            'nombre' => 'Supermercado B',
            'NIT' => '0987654321',
            'direccion' => 'Avenida XYZ, Ciudad B',
            'logo' => 'supermercado_b.png',
            'latitud' => '789.123',
            'longitud' => '321.654',
            'ID_ciudad' => 1,
        ]);
    }
}
