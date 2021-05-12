<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoEquipo;
use View;
use Illuminate\Support\Facades\DB;

class TipoEquipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('tipo_equipo.index');
    }

    public function fetchTipoEquipo()
    {
        $tipo_equipo=DB::table('tipo_equipo')->orderBy('nombre_tipo_equipo','asc')->get();

        return response()->json([
            "tipo_equipo" => $tipo_equipo
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $tipo_equipo=new TipoEquipo;
            $tipo_equipo->nombre_tipo_equipo=$request->nombre_tipo_equipo;
            $tipo_equipo->save();

            return response()->json([
                "message" => "TipoEquipo creado correctamente.",
                "id_tipo_equipo" => $tipo_equipo->id_tipo_equipo
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

            $tipo_equipo=TipoEquipo::find($id);
            $tipo_equipo->nombre_tipo_equipo=$request->nombre_tipo_equipo;
            $tipo_equipo->save();

            return response()->json([
                "message" => "TipoEquipo actualizado correctamente."
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
