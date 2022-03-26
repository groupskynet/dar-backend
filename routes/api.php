<?php

use App\Http\Controllers\AccesoriosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\GastosController;
use App\Http\Controllers\MaquinasController;
use App\Http\Controllers\MarcasController;
use App\Http\Controllers\OperadoresController;
use App\Http\Controllers\OrdenServicioController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\TicketsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::resource('ordenServicio', OrdenServicioController::class);
Route::resource('tickets', TicketsController::class);

Route::group(['prefix' => 'literales'], function () {
    Route::get('marcas/all', [MarcasController::class, 'all']);
    Route::get('maquinas/all', [MaquinasController::class, 'all']);
    Route::get('clientes/all', [ClientesController::class, 'all']);
    Route::get('operadores/all', [OperadoresController::class, 'all']);
    Route::get('accesorios/all', [AccesoriosController::class, 'all']);
    Route::get('proveedores/all', [ProveedoresController::class, 'all']);
    Route::get('ordenServicio/all', [OrdenServicioController::class, 'all']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
