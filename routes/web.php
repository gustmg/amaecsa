<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('logout', 'Auth\LoginController@logout');

Route::post('/vuelogin', 'Auth\LoginController@vuelogin');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('personal', 'PersonalController')->middleware('auth');
Route::post('fetchPersonal', 'PersonalController@fetchPersonal')->middleware('auth');

Route::resource('destinos', 'DestinoController')->middleware('auth');
Route::post('fetchDestinos', 'DestinoController@fetchDestinos')->middleware('auth');

Route::resource('marcas', 'MarcaController')->middleware('auth');
Route::post('fetchMarcas', 'MarcaController@fetchMarcas')->middleware('auth');

Route::resource('categorias', 'CategoriaController')->middleware('auth');
Route::post('fetchCategorias', 'CategoriaController@fetchCategorias')->middleware('auth');

Route::resource('equipos', 'EquipoController')->middleware('auth');
Route::post('fetchEquipos', 'EquipoController@fetchEquipos')->middleware('auth');

Route::post('fetchUnidadesMedida', 'UnidadMedidaController@fetchUnidadesMedida')->middleware('auth');

Route::resource('entradas', 'EntradaController')->middleware('auth');
Route::post('fetchEntradas', 'EntradaController@fetchEntradas')->middleware('auth');

Route::resource('salidas', 'SalidaController')->middleware('auth');
Route::post('fetchSalidas', 'SalidaController@fetchSalidas')->middleware('auth');

Route::resource('prestamos', 'PrestamoController')->middleware('auth');
Route::post('fetchPrestamos', 'PrestamoController@fetchPrestamos')->middleware('auth');

Route::post('createBackup', function(){
    $filename="backup-".date("d-m-Y-H-i").".sql";
    $mysqlPath="C:\\xampp/mysql/bin/mysqldump";
    $file_path="C:\\Laravel";
    try{
        $command = "$mysqlPath --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  > " . $file_path . "/" . $filename."  2>&1";
        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);
        return 1;
     }catch(Exception $e){
        return "0 ".$e->errorInfo;
     }
});