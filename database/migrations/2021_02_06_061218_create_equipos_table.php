<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->increments('id_equipo');
            $table->string('nombre_equipo');
            $table->string('codigo_producto_equipo');
            $table->string('codigo_barras_equipo')->nullable();
            $table->boolean('desechable');
            $table->float('stock_equipo')->default(0);
            $table->unsignedInteger('id_marca_equipo');
            $table->unsignedInteger('id_tipo_equipo');
            $table->unsignedInteger('id_categoria_equipo');
            $table->unsignedInteger('id_unidad_medida_equipo');
            $table->timestamps();

            $table->foreign('id_marca_equipo')->references('id_marca')->on('marcas');
            $table->foreign('id_tipo_equipo')->references('id_tipo_equipo')->on('tipo_equipo');
            $table->foreign('id_categoria_equipo')->references('id_categoria')->on('categorias');
            $table->foreign('id_unidad_medida_equipo')->references('id_unidad_medida')->on('unidades_medida');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipos');
    }
}
