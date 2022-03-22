<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;


class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes=Clientes::paginate(10);
        return response()->json([
            'status'=> Response::HTTP_OK,
            'message'=>'succes',
            'data'=>$clientes
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
        $validate= Validator::make($request->all(),[
            'tipo'=>'required',
            'cedula'=>'numeric|required|unique:clientes',
            'nombres'=>'required',
            'telefono1'=>'numeric|required',
            'direccion'=>'required',
            'email'=>'required'
        ]);
        if($validate->fails()){
            return response()->json([
            'status'=>Response::HTTP_BAD_REQUEST,
            'message'=>'invalid data'
        ],Response::HTTP_OK);
    }
    
    $clientes = new Clientes($request->all());
    $clientes->nombres = strtoupper($request->nombres);
    $clientes->direccion = strtoupper($request->direccion);
    $clientes->email= strtoupper($request->email);
    if($clientes->razonSocial !== null){
        $clientes->razonSocial= strtoupper($request->razonSocial);
    }
    $result = $clientes->save();

    if($result){
        return response()->json([
            'status'=>Response::HTTP_OK,
            'message'=>'datos guardados correctamente'
        ],Response::HTTP_OK);
    }
    return response()->json([
        'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
        'message'=>'Error de servidor'
    ],Response::HTTP_OK);
}
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function edit(Clientes $clientes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $validate=Validator::make($request->all(),[
            'tipo'=>'required',
            'cedula'=>'numeric|required|unique:clientes,cedula'.$id,
            'nombres'=>'required',
            'telefono1'=>'numeric|required',
            'direccion'=>'required',
            'email'=>'required'      
        ]);

        if($validate->fails()){
            return response()->json([
                'status'=>Response::HTTP_BAD_REQUEST,
                'message'=>'invalid data'
            ],Response::HTTP_OK);
        }

        $clientes=Clientes::find($id);
        $clientes->fill($request->all());
        $clientes->nombres=strtoupper($request->nombre);
        $clientes->direccion=strtoupper($request->direccion);
        $clientes->email=strtoupper($request->email);
        
        if($clientes->razonSocial !== null){
            $clientes->razonSocial=strtoupper($request->razonSocial);
        }
        $result = $clientes->save();

        if($result){
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'Datos Actualizados correctamente'
            ],Response::HTTP_OK);
            return response()->json([
                'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'=>'Error de servidor'
            ],Response::HTTP_OK);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clientes  $clientes
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente =Clientes::find($id);
        if($cliente!==null){
            $cliente->delete();
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'cliente eliminado correctamente'
            ]);
        }
        return response()->json([
            'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'=>'Error de servidor'
        ],Response::HTTP_OK);
    }
}
