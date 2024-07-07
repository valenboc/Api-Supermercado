<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CiudadesSeeder extends Seeder{

    public function run(): void{
        DB::table('ciudades')->insert([
            ['Nombre' => 'Santa Marta'],
            ['Nombre' => 'Cali'],
            ['Nombre' => 'Bogota'],
            ['Nombre' => 'Medellin'],
        ]);
    }
}
