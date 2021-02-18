<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Marca;
use View;
use Illuminate\Support\Facades\DB;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('marcas.index');
    }

    public function fetchMarcas()
    {
        $marcas=Marca::all();

        return response()->json([
            "marcas" => $marcas
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
            $marca=new Marca;
            $marca->nombre_marca=$request->nombre_marca;
            $marca->save();

            return response()->json([
                "message" => "Marca creado correctamente.",
                "id_marca" => $marca->id_marca
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

            $marca=Marca::find($id);
            $marca->nombre_marca=$request->nombre_marca;
            $marca->save();

            return response()->json([
                "message" => "Marca actualizada correctamente."
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
