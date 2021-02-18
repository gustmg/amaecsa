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