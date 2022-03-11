<?php

namespace App\Http\Controllers;

use App\Models\Maquinas;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MaquinasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $maquinas = Maquinas::with('marca')->get();
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $maquinas
        ], Response::HTTP_OK);
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
            'tipo' => 'required',
        ]);

        if($validate->fails()){
            return response()->json($validate->errors());
            return response()->json([
                'status' =>  Response::HTTP_BAD_REQUEST,
                'message' => 'invalid data'
            ],Response::HTTP_OK);
        }

        $maquinas = new Maquinas($request->all());
        $result = $maquinas->save();
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
     * @param  \App\Models\Maquinas  $maquinas
     * @return \Illuminate\Http\Response
     */
    public function show(Maquinas $maquinas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Maquinas  $maquinas
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Maquinas $maquina)
    {

        $validate = Validator::make($request->all(),[
            'nombre' => 'required',
            'serie' => 'required',
            'marca' => 'numeric|required',
            'modelo' => 'required',
            'linea' => 'required',
            'registro' => 'required',
            'tipo' => 'required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' =>  Response::HTTP_BAD_REQUEST,
                'message' => 'invalid data'
            ],Response::HTTP_OK);
        }

        $maquina = $maquina->fill($request->all());
        $result= $maquina->save();
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
     * @param  \App\Models\Maquinas  $maquinas
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $maquina = Maquinas::find($id);
        if($maquina){
            $maquina->delete();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Maquina eliminada correctamente'
            ]);
        }
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Error del servidor'
        ],Response::HTTP_OK);
    }
}
