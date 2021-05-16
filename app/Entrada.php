<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    protected $table = 'entradas';
    protected $primaryKey  = 'id_entrada';
    protected $with =['equipos', 'destino'];

    public function equipos(){
        return $this->belongsToMany('App\Equipo', 'entradas_equipo', 'id_entrada', 'id_equipo')->withPivot('cantidad', 'costo_unitario');
    }

    public function destino(){
        return $this->belongsTo('App\Destino', 'id_destino_entrada');
    }
}
