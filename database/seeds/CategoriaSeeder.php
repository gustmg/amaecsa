<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categorias')->insert([
            'nombre_categoria' => 'Herramientas',
            'codigo_categoria' => 'HERR1',
        ]);

        DB::table('categorias')->insert([
            'nombre_categoria' => 'Papeleria',
            'codigo_categoria' => 'PAP1',
        ]);

        DB::table('categorias')->insert([
            'nombre_categoria' => 'Médico',
            'codigo_categoria' => 'MED1',
        ]);

        DB::table('categorias')->insert([
            'nombre_categoria' => 'Cables',
            'codigo_categoria' => 'CAB1',
        ]);
    }
}
