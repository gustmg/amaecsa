<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Personal;
use View;
use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('personal.index');
    }

    public function fetchPersonal()
    {
        $personal=Personal::all();

        return response()->json([
            "personal" => $personal
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
            $personal=new Personal;
            $personal->nombre_personal=$request->nombre_personal;
            $personal->codigo_personal=$request->codigo_personal;
            $personal->save();

            return response()->json([
                "message" => "Personal creado correctamente.",
                "personal_id" => $personal->id_personal
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

            $personal=Personal::find($id);
            $personal->nombre_personal=$request->nombre_personal;
            $personal->codigo_personal=$request->codigo_personal;
            $personal->save();

            return response()->json([
                "message" => "Personal actualizado correctamente."
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
