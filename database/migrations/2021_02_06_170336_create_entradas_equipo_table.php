<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntradasEquipoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entradas_equipo', function (Blueprint $table) {
            $table->unsignedInteger('id_entrada');
            $table->unsignedInteger('id_equipo');
            $table->float('cantidad');
            $table->float('costo_unitario');

            $table->foreign('id_entrada')->references('id_entrada')->on('entradas');
            $table->foreign('id_equipo')->references('id_equipo')->on('equipos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entradas_equipo');
    }
}
