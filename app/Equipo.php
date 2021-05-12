<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'equipos';
    protected $primaryKey  = 'id_equipo';
    protected $with= ['marca', 'categoria', 'unidad_medida', 'tipo_equipo'];

    public function marca(){
        return $this->belongsTo('App\Marca', 'id_marca_equipo');
    }

    public function categoria(){
        return $this->belongsTo('App\Categoria', 'id_categoria_equipo');
    }

    public function unidad_medida(){
        return $this->belongsTo('App\UnidadMedida', 'id_unidad_medida_equipo');
    }

    public function tipo_equipo(){
        return $this->belongsTo('App\TipoEquipo', 'id_tipo_equipo');
    }
}
