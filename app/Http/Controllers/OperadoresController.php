<?php

namespace App\Http\Controllers;

use App\Models\Operadores;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OperadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $operadores= Operadores::all(['nombres','apellidos','cedula','telefono1','telefono2','licencia','direccion','email']);
        return response()->json([
            'status'=> Response::HTTP_OK,
            'message'=>'success',
            'data'=>$operadores
        ], Response::HTTP_OK);
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
            'nombres'=>'required',
            'apellidos'=>'required',
            'cedula'=>'numeric|required',
            'telefono1'=>'numeric|required',
            'licencia'=>'required',
            'direccion'=>'required',
            'email'=>'required'
        ]);
        if($validate->fails()){
            return response()->json([
                'status'=>Response::HTTP_BAD_REQUEST,
                'message'=>'invalid data'
            ],Response::HTTP_OK);
        }

        $operadores= new Operadores($request->all());
        $operadores->nombres = strtoupper($request->nombres);
        $operadores->apellidos = strtoupper($request->apellidos);
        $operadores->direccion = strtoupper($request->direccion);
        $operadores->email = strtoupper($request->email);
        $result = $operadores->save();

        if($result){
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'datos guardados correctamente'
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status'=> Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'=> 'Error de servidor'
        ], Response::HTTP_OK); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Operadores  $operadores
     * @return \Illuminate\Http\Response
     */
    public function show(Operadores $operadores)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Operadores  $operadores
     * @return \Illuminate\Http\Response
     */
    public function edit(Operadores $operadores)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Operadores  $operadores
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(),[
            'nombres'=>'required',
            'apellidos' => 'required',
            'cedula'=>'numeric|required',
            'telefono1'=> 'numeric|required',
            'telefono2'=> 'numeric|required',
            'licencia'=> 'required',
            'direccion'=> 'required',
            'email'=> 'required'
        ]);

        if($validate->fails()){
            return response()->json([
                'status'=>Response::HTTP_BAD_REQUEST,
                'message'=> 'invalid data'
            ],Response::HTTP_OK);
        }

        $operadores = Operadores::find($id);
        $operadores->fill($request->all());
        $operadores->nombres = strtoupper($request->nombres);
        $operadores->apellidos = strtoupper($request->apellidos);
        $operadores->direccion = strtoupper($request->direccion);
        $operadores->email= strtoupper($request->email);
        $result = $operadores->save();

        if($result){
            return response()->json([
                'status'=> Response::HTTP_OK,
                'message'=> 'Datos guardador correctamente'
            ],Response::HTTP_OK);
            return response()->json([
                'status'=> Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'=> 'Error en el servidor'
            ],Response::HTTP_OK);
        }
        
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Operadores  $operadores
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $operador = Operadores::find($id);
        if($operador){
            $operador->delete();
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'operador eliminado coreectamente'
            ]);
        }   
        return response()->json([
            'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'=>'Error en el servidor'
        ],Response::HTTP_OK);
    }
}
