<?php

namespace App\Http\Controllers;

use App\Models\Asignaciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class AsignacionesController extends Controller
{

    public function index()
    {
        $asignaciones = Asignaciones::paginate(10);
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $asignaciones
        ], Response::HTTP_OK);
    }

    public function all()
    {
        $asignaciones = Asignaciones::all();
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $asignaciones
        ], Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'operador' => 'required',
            'maquina' => 'required',
            'fecha_inicio'=>'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'invalid data'
            ], Response::HTTP_OK);
        }

        $asignacion = new Asignaciones();
        $asignacion->operador = strtoupper($request->operador);
        $asignacion->maquina = strtoupper($request->maquina);
        $asignacion->fechaInicio = $request->fechaInicio;
        $asignacion->fechaFin = $request->fechaFin;
        $result = $asignacion->save();
        if ($result) {
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
}