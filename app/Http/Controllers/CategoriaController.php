<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use View;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('categorias.index');
    }

    public function fetchCategorias()
    {
        $categorias=DB::table('categorias')->orderBy('nombre_categoria','asc')->get();

        return response()->json([
            "categorias" => $categorias
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
            $categoria=new Categoria;
            $categoria->nombre_categoria=$request->nombre_categoria;
            $categoria->codigo_categoria=$request->codigo_categoria;
            $categoria->save();

            return response()->json([
                "message" => "Categoria creado correctamente.",
                "id_categoria" => $categoria->id_categoria
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

            $categoria=Categoria::find($id);
            $categoria->nombre_categoria=$request->nombre_categoria;
            $categoria->codigo_categoria=$request->codigo_categoria;
            $categoria->save();

            return response()->json([
                "message" => "Categoria actualizado correctamente."
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
