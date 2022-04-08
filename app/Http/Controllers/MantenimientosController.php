<?php

namespace App\Http\Controllers;

use App\Models\Mantenimientos;
use App\Models\Proveedores;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Console;
use SebastianBergmann\Environment\Console as EnvironmentConsole;

class MantenimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mantenimientos = Mantenimientos::with('proveedor','maquina')->paginate(10);
        return response()->json([
            'status'=>Response::HTTP_OK,
            'message'=>'success',
            'data'=>$mantenimientos
        ],Response::HTTP_OK);
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
        $validate = Validator::make($request->all(),[
            'tipo'=>'required',
            'maquina'=>'numeric|required',
            'proveedor'=> 'numeric|required',
            'descripcion'=>'required',
            'horometro'=>'numeric|required',
            'modalidad'=>'required',
            'costo'=>'required',
            'soporte'=>'required|mimes:pdf'

        ]);

        if($validate->fails()){
            return response()->json([
                'status'=>Response::HTTP_BAD_REQUEST,
                'message'=>'invalid data',
                'data'=> $validate->errors()
            ],Response::HTTP_OK);
        }

        $path='';

        if($request->hasFile('soporte')){
            $path = $request->file('soporte')->storeAs(
                'soportes', 'soporte-'. $request->proveedor . '-' . date('Y-m-d-hh:mm') . '-' . $request->file('soporte')->getClientOriginalName()
            );
        }

        $mantenimiento = new Mantenimientos($request->all());
        $mantenimiento->soporte = $path;
        $mantenimiento->descripcion = strtoupper($request->descripcion);
        $resullt = $mantenimiento->save();

        if($resullt){
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'Datos guardados correctamente'
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'=> 'Error de servidor'
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Mantenimientos $mantenimiento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mantenimientos $mantenimiento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mantenimiento  $mantenimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mantenimientos $mantenimiento)
    {
        //
    }
}
