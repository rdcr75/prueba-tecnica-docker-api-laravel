<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;
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
    return view('welcome');
});

/*
    GET: Obtener datos o recursos
    POST: Guardar datos o recursos o hacer lógica desde un formulario
    PUT: 
    DELETE:
*/

# TEST DE RUTAS PARA LA API
Route::get('/usuario/test', 'App\Http\Controllers\UserController@test');

# Rutas del controlador de servicios
Route::get('api/search', 'App\Http\Controllers\ServiciosMercadoLibreController@search');
