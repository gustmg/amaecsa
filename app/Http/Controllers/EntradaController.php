<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entrada;
use App\Equipo;
use View;
use Illuminate\Support\Facades\DB;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('entradas.index');
    }

    public function fetchEntradas()
    {
        $entradas=Entrada::all();

        return response()->json([
            "entradas" => $entradas
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
            $entrada=new Entrada;
            $entrada->numero_vale_entrada=$request->numero_vale_entrada;
            $entrada->id_destino_entrada=$request->id_destino_entrada;
            $entrada->save();
            
            foreach($request->entradas as $key=>$entrada_equipo){
                $entrada->equipos()->attach($entrada_equipo["id_equipo"], ['cantidad'=>$entrada_equipo["cantidad"], 'costo_unitario'=>$entrada_equipo["costo_unitario"]]);
                
                $equipo=Equipo::find($entrada_equipo["id_equipo"]);
                $equipo->stock_equipo=$equipo->stock_equipo+$entrada_equipo["cantidad"];
                $equipo->save();
            }

            return response()->json([
                "message" => "Entrada creada correctamente.",
                "id_entrada" => $entrada->id_entrada
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
