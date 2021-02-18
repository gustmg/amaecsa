<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table = 'prestamos';
    protected $primaryKey  = 'id_prestamo';
    protected $with= ['personal', 'equipo', 'destino'];

    public function personal(){
        return $this->belongsTo('App\Personal', 'id_personal');
    }

    public function equipo(){
        return $this->belongsTo('App\Equipo', 'id_equipo');
    }

    public function destino(){
        return $this->belongsTo('App\Destino', 'id_destino');
    }
}
