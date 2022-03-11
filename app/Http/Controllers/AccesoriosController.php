<?php

namespace App\Http\Controllers;

use App\Models\Accesorios;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AccesoriosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accesorios= Accesorios::with('marca','maquina')->get();
        return response()->json([ 
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data'=> $accesorios
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
            'nombre' => 'required',
            'serie' => 'required',
            'marca' => 'numeric|required',
            'modelo' => 'required',
            'linea' => 'required',
            'registro' => 'required',
            'maquina' => 'numeric|required',
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
            return response()->json([
                'status' =>  Response::HTTP_BAD_REQUEST,
                'message' => 'invalid data'
            ],Response::HTTP_OK);
        }

        $accesorios = new Accesorios($request->all());
        $result = $accesorios->save();
        if($result){
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Datos guardados correctamente'
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Error de servidor'
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accesorios  $accesorios
     * @return \Illuminate\Http\Response
     */
    public function show(Accesorios $accesorios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accesorios  $accesorios
     * @return \Illuminate\Http\Response
     */
    public function edit(Accesorios $accesorios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accesorios  $accesorios
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Accesorios $accesorio)
    {
        $validate = Validator::make($request->all(),[
            'nombre' => 'required',
            'serie' => 'required',
            'marca' => 'numeric|required',
            'modelo' => 'required',
            'linea' => 'required',
            'registro' => 'required',
            'maquina' => 'numeric|required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' =>  Response::HTTP_BAD_REQUEST,
                'message' => 'invalid data'
            ],Response::HTTP_OK);
        }

        $accesorio = $accesorio->fill($request->all());
        $result= $accesorio->save();
        if($result){
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Datos guardados correctamente'
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Error de servidor'
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accesorios  $accesorios
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $accesorio = Accesorios::find($id);
        if($accesorio){
            $accesorio->delete();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Accesorio eliminado correctamente'
            ]);
        }
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Error del servidor'
        ],Response::HTTP_OK);
    }
}
