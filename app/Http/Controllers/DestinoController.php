<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Destino;
use View;
use Illuminate\Support\Facades\DB;

class DestinoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('destinos.index');
    }

    public function fetchDestinos()
    {
        $destinos=Destino::all();

        return response()->json([
            "destinos" => $destinos
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
            $destino=new Destino;
            $destino->nombre_destino=$request->nombre_destino;
            $destino->save();

            return response()->json([
                "message" => "Destino creado correctamente.",
                "id_destino" => $destino->id_destino
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

            $destino=Destino::find($id);
            $destino->nombre_destino=$request->nombre_destino;
            $destino->save();

            return response()->json([
                "message" => "Destino actualizado correctamente."
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
