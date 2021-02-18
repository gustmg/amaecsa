<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DestinoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('destinos')->insert([
            'nombre_destino' => 'Taller',
        ]);

        DB::table('destinos')->insert([
            'nombre_destino' => 'Oficina',
        ]);

        DB::table('destinos')->insert([
            'nombre_destino' => 'Tracto A',
        ]);
    }
}
