<?php

namespace App\Http\Controllers;

use App\Models\Maquinas;
use App\Models\Asignaciones;
use App\Models\Operadores;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Operator;

class MaquinasController extends Controller
{

    public function index()
    {
        $maquinas = Maquinas::with('marca', 'accesorios','operador')->paginate(10);
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $maquinas
        ], Response::HTTP_OK);
    }
    public function all()
    {
        $maquinas= Maquinas::with('accesorios','operador')->get();
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $maquinas
        ], Response::HTTP_OK);
    }

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
            'horometro' => 'numeric|required',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' =>  Response::HTTP_BAD_REQUEST,
                'message' => $validate->errors()->first(),
                'data' => $validate->errors()
            ],Response::HTTP_OK);
        }
        $maquina = new Maquinas($request->all());
        $maquina->nombre = strtoupper($request->nombre);
        $maquina->serie = strtoupper($request->serie);
        $maquina->linea = strtoupper($request->linea);
        $maquina->registro = strtoupper($request->registro);
        $result= $maquina->save();
        if($result){
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Datos guardados correctamente',
                'data' => $maquina
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Error de servidor'
        ], Response::HTTP_OK);

    }

    public function update(Request $request, $id)
    {

        $validate = Validator::make($request->all(),[
            'nombre' => 'required',
            'serie' => 'required',
            'marca' => 'numeric|required',
            'modelo' => 'numeric|required',
            'horometro' => 'numeric|required',
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


        $maquina = Maquinas::find($id);
        $horometro = $maquina->horometro;
        $maquina->fill($request->all());
        $maquina->nombre = strtoupper($request->nombre);
        $maquina->serie = strtoupper($request->serie);
        $maquina->linea = strtoupper($request->linea);
        $maquina->registro = strtoupper($request->registro);
        $maquina->horometro = $horometro;
        $result= $maquina->save();
        if($result){
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Datos guardados correctamente',
                'data' => $maquina
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Error de servidor'
        ], Response::HTTP_OK);
    }


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
