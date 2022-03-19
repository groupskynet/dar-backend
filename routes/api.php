<?php

use App\Http\Controllers\AccesoriosController;
use App\Http\Controllers\MarcasController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaquinasController;
use App\Http\Controllers\OperadoresController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\GastosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::resource('marcas', MarcasController::class);
Route::resource('maquinas', MaquinasController::class);
Route::resource('accesorios', AccesoriosController::class);
Route::resource('operadores', OperadoresController::class);
Route::resource('clientes', ClientesController::class);
Route::resource('proveedores', ProveedoresController::class);
Route::resource('gastos', GastosController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
