<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnidadMedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unidades_medida')->insert([
            'nombre_unidad_medida' => 'Metros',
        ]);
        DB::table('unidades_medida')->insert([
            'nombre_unidad_medida' => 'Kilos',
        ]);
        DB::table('unidades_medida')->insert([
            'nombre_unidad_medida' => 'Litros',
        ]);
        DB::table('unidades_medida')->insert([
            'nombre_unidad_medida' => 'Piezas',
        ]);
    }
}
