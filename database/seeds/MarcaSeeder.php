<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('marcas')->insert([
            'nombre_marca' => 'Trupper',
        ]);

        DB::table('marcas')->insert([
            'nombre_marca' => 'Genérica',
        ]);

        DB::table('marcas')->insert([
            'nombre_marca' => 'Medicare',
        ]);

        DB::table('marcas')->insert([
            'nombre_marca' => 'Black & decker',
        ]);
    }
}
