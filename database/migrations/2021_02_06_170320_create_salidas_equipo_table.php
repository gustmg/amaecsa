<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalidasEquipoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salidas_equipo', function (Blueprint $table) {
            $table->unsignedInteger('id_salida');
            $table->unsignedInteger('id_equipo');
            $table->float('cantidad');
            $table->string('comentario');
            
            $table->foreign('id_salida')->references('id_salida')->on('salidas');
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
        Schema::dropIfExists('salidas_equipo');
    }
}
