<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('personal')->insert([
            'nombre_personal' => 'Hugo',
            'codigo_personal' => '0001',
        ]);
        DB::table('personal')->insert([
            'nombre_personal' => 'Paco',
            'codigo_personal' => '0002',
        ]);
        DB::table('personal')->insert([
            'nombre_personal' => 'Luis',
            'codigo_personal' => '0003',
        ]);
    }
}
