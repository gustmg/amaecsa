<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestamosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->increments('id_prestamo');
            $table->unsignedInteger('id_personal');
            $table->unsignedInteger('id_equipo');
            $table->unsignedInteger('id_destino');
            $table->float('cantidad')->nullable();
            $table->boolean('recibido')->nullable();
            $table->timestamps();

            $table->foreign('id_personal')->references('id_personal')->on('personal');
            $table->foreign('id_equipo')->references('id_equipo')->on('equipos');
            $table->foreign('id_destino')->references('id_destino')->on('destinos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prestamos');
    }
}
