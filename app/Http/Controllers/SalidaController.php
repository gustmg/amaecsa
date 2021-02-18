<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Salida;
use App\Equipo;
use View;
use Illuminate\Support\Facades\DB;

class SalidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('salidas.index');
    }

    public function fetchSalidas()
    {
        $salidas=Salida::all();

        return response()->json([
            "salidas" => $salidas
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax()){
            $salida=new Salida;
            $salida->save();
            
            foreach($request->salidas as $key=>$salida_equipo){
                $salida->equipos()->attach($salida_equipo["id_equipo"], ['cantidad'=>$salida_equipo["cantidad"], 'comentario'=>$salida_equipo["comentario"]]);
                

                $equipo=Equipo::find($salida_equipo["id_equipo"]);
                $equipo->stock_equipo=$equipo->stock_equipo-$salida_equipo["cantidad"];
                $equipo->save();
            }

            return response()->json([
                "message" => "Salida creada correctamente.",
                "id_salida" => $salida->id_salida
            ],200);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
