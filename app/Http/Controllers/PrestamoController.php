<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prestamo;
use App\Equipo;
use View;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('prestamos.index');
    }

    public function fetchPrestamos()
    {
        $prestamos=Prestamo::all();

        return response()->json([
            "prestamos" => $prestamos
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
            foreach($request->prestamos as $key=>$equipo_prestamo){
                $prestamo=new Prestamo;
                $prestamo->id_personal=$equipo_prestamo["id_personal"];
                $prestamo->id_equipo=$equipo_prestamo["id_equipo"];
                $prestamo->id_destino=$equipo_prestamo["id_destino"];
                $prestamo->cantidad=$equipo_prestamo["cantidad"];
                if($equipo_prestamo["desechable"]==0){
                    $prestamo->recibido=0;                    
                }
                $prestamo->save();

                $equipo=Equipo::find($equipo_prestamo["id_equipo"]);
                $equipo->stock_equipo=$equipo->stock_equipo-$equipo_prestamo["cantidad"];
                $equipo->save();
            }

            

            return response()->json([
                "message" => "Prestamo creado correctamente.",
                "id_prestamo" => $prestamo->id_prestamo
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
        if($request->ajax()){
            $prestamo=Prestamo::find($id);
            $prestamo->recibido=1;
            $prestamo->save();

            $equipo=Equipo::find($prestamo->id_equipo);
            $equipo->stock_equipo=$equipo->stock_equipo+1;
            $equipo->save();

            return response()->json([
                "message" => "Prestamo actualizado correctamente.",
            ],200);
        }
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
