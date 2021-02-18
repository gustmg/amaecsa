<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'equipos';
    protected $primaryKey  = 'id_equipo';
    protected $with= ['marca', 'categoria', 'unidad_medida'];

    public function marca(){
        return $this->belongsTo('App\Marca', 'id_marca_equipo');
    }

    public function categoria(){
        return $this->belongsTo('App\Categoria', 'id_categoria_equipo');
    }

    public function unidad_medida(){
        return $this->belongsTo('App\UnidadMedida', 'id_unidad_medida_equipo');
    }

    public function entradas(){
        return $thjis->belongsToMany('App\Entrada', 'entradas_equipo', 'id_equipo', 'id_entrada');
    }
}
