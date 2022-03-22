<?php

namespace App\Http\Controllers;

use App\Models\Proveedores;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProveedoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $proveedores=Proveedores::paginate(10);
        return response()->json([
            'status'=> Response::HTTP_OK,
            'message'=>'succes',
            'data'=>$proveedores
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
            'cedula'=>'numeric|required|unique:proveedores',
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
    
    $proveedores = new Proveedores($request->all());
    $proveedores->nombres = strtoupper($request->nombres);
    $proveedores->direccion = strtoupper($request->direccion);
    $proveedores->email= strtoupper($request->email);
    if($proveedores->razonSocial !== null){
        $proveedores->razonSocial= strtoupper($request->razonSocial);
    }
    $result = $proveedores->save();

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
     * Display the specified resource.
     *
     * @param  \App\Models\Proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function show(Proveedores $proveedores)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedores $proveedores)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(),[
            'tipo'=>'required',
            'cedula'=>'numeric|required|unique:proveedores,cedula'.$id,
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

        $proveedores=Proveedores::find($id);
        $proveedores->fill($request->all());
        $proveedores->nombres=strtoupper($request->nombre);
        $proveedores->direccion=strtoupper($request->direccion);
        $proveedores->email=strtoupper($request->email);
        
        if($proveedores->razonSocial !== null){
            $proveedores->razonSocial=strtoupper($request->razonSocial);
        }
        $result = $proveedores->save();

        if($result){
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'Datos Actualizados correctamente'
            ],Response::HTTP_OK);
            return response()->json([
                'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'=>'Errod de servidor'
            ],Response::HTTP_OK);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Proveedores  $proveedores
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $proveedor = Proveedores::find($id);
        if($proveedor !== null){
            $proveedor->delete();
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'Proveedor eliminado correctamente'
            ]);
        }
        return response()->json([
            'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'=>'Error de servidor'
        ],Response::HTTP_OK);
 
    }
}
