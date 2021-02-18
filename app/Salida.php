<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    protected $table = 'salidas';
    protected $primaryKey  = 'id_salida';
    protected $with =['equipos'];

    public function equipos(){
        return $this->belongsToMany('App\Equipo', 'salidas_equipo', 'id_salida', 'id_equipo')->withPivot('cantidad', 'comentario');
    }
}
