<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipo;
use View;
use Illuminate\Support\Facades\DB;

class EquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('equipos.index');
    }

    public function fetchEquipos()
    {
        $equipos=Equipo::all();

        return response()->json([
            "equipos" => $equipos
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
            $equipo=new Equipo;
            $equipo->nombre_equipo=$request->nombre_equipo;
            $equipo->codigo_producto_equipo=$request->codigo_producto_equipo;
            $equipo->codigo_barras_equipo=$request->codigo_barras_equipo;
            $equipo->desechable=$request->desechable;
            $equipo->id_marca_equipo=$request->id_marca_equipo;
            $equipo->id_tipo_equipo=$request->id_tipo_equipo;
            $equipo->id_categoria_equipo=$request->id_categoria_equipo;
            $equipo->id_unidad_medida_equipo=$request->id_unidad_medida_equipo;
            $equipo->save();

            return response()->json([
                "message" => "Personal creado correctamente.",
                "id_equipo" => $equipo->id_equipo
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
            $equipo=Equipo::find($id);
            $equipo->nombre_equipo=$request->nombre_equipo;
            $equipo->codigo_producto_equipo=$request->codigo_producto_equipo;
            $equipo->codigo_barras_equipo=$request->codigo_barras_equipo;
            $equipo->desechable=$request->desechable;
            $equipo->id_marca_equipo=$request->id_marca_equipo;
            $equipo->id_tipo_equipo=$request->id_tipo_equipo;
            $equipo->id_categoria_equipo=$request->id_categoria_equipo;
            $equipo->id_unidad_medida_equipo=$request->id_unidad_medida_equipo;
            $equipo->save();

            return response()->json([
                "message" => "Equipo editado correctamente.",
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
