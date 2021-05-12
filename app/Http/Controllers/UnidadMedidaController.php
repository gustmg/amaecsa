<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UnidadMedida;
use View;


class UnidadMedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('unidades_medida.index');
    }

    public function fetchUnidadesMedida()
    {
        $unidades_medida=UnidadMedida::all();

        return response()->json([
            "unidades_medida" => $unidades_medida
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            $unidad_medida=new UnidadMedida;
            $unidad_medida->nombre_unidad_medida=$request->nombre_unidad_medida;
            $unidad_medida->save();

            return response()->json([
                "message" => "unidad_medida creado correctamente.",
                "id_unidad_medida" => $unidad_medida->id_unidad_medida
            ],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            $unidad_medida=UnidadMedida::find($id);
            $unidad_medida->nombre_unidad_medida=$request->nombre_unidad_medida;
            $unidad_medida->save();

            return response()->json([
                "message" => "unidad_medida actualizada correctamente."
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
