<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('equipos')->insert([
            'nombre_equipo' => 'Martillo',
            'codigo_barras_equipo' => '123456789',
            'desechable' => 0,
            'id_marca_equipo' => 1,
            'id_categoria_equipo' => 1,
            'id_unidad_medida_equipo' => 4,
        ]);

        DB::table('equipos')->insert([
            'nombre_equipo' => 'Tanque de oxígeno',
            'codigo_barras_equipo' => '987654321',
            'desechable' => 0,
            'id_marca_equipo' => 4,
            'id_categoria_equipo' => 1,
            'id_unidad_medida_equipo' => 4,
        ]);

        DB::table('equipos')->insert([
            'nombre_equipo' => 'Cable',
            'codigo_barras_equipo' => '0000055555',
            'desechable' => 1,
            'id_marca_equipo' => 2,
            'id_categoria_equipo' => 4,
            'id_unidad_medida_equipo' => 1,
        ]);

        DB::table('equipos')->insert([
            'nombre_equipo' => 'Cubrebocas',
            'codigo_barras_equipo' => '11122233344',
            'desechable' => 1,
            'id_marca_equipo' => 3,
            'id_categoria_equipo' => 3,
            'id_unidad_medida_equipo' => 4,
        ]);
    }
}
