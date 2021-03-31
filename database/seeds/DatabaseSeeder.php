<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call('UserSeeder');
        $this->call('UnidadMedidaSeeder');
        // $this->call('PersonalSeeder');
        // $this->call('CategoriaSeeder');
        // $this->call('MarcaSeeder');
        // $this->call('DestinoSeeder');
        // $this->call('EquipoSeeder');
    }
}
