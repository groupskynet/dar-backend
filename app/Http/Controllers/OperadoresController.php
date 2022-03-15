<?php

namespace App\Http\Controllers;

use App\Models\Operadores;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OperadoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $operadores = Operadores::all();
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $operadores
        ], Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nombres' => 'required',
            'apellidos' => 'required',
            'cedula' => 'numeric|required|unique:operadores',
            'telefono1' => 'numeric|required',
            'licencia' => 'required|mimes:pdf',
            'direccion' => 'required',
            'email' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'invalid data'
            ], Response::HTTP_OK);
        }

        $path = '';

        if ($request->hasFile('licencia')) {
            $path = $request->file('licencia')->storeAs(
                'licencias', 'licencia-' . $request->cedula . '-' . date('Y-m-d-hh:mm') . '-' . $request->file('licencia')->getClientOriginalName()
            );
        }

        $operadores = new Operadores($request->all());
        $operadores->nombres = strtoupper($request->nombres);
        $operadores->apellidos = strtoupper($request->apellidos);
        $operadores->direccion = strtoupper($request->direccion);
        $operadores->email = strtoupper($request->email);
        $operadores->licencia = $path;
        $result = $operadores->save();

        if ($result) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'datos guardados correctamente'
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
     * @param Operadores $operadores
     * @return Response
     */
    public function show(Operadores $operadores)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Operadores $operadores
     * @return Response
     */
    public function edit(Operadores $operadores)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Operadores $operadores
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'nombres' => 'required',
            'apellidos' => 'required',
            'cedula' => 'numeric|required',
            'telefono1' => 'numeric|required',
            'telefono2' => 'numeric|required',
            'licencia' => 'required',
            'direccion' => 'required',
            'email' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'invalid data'
            ], Response::HTTP_OK);
        }

        $operadores = Operadores::find($id);
        $operadores->fill($request->all());
        $operadores->nombres = strtoupper($request->nombres);
        $operadores->apellidos = strtoupper($request->apellidos);
        $operadores->direccion = strtoupper($request->direccion);
        $operadores->email = strtoupper($request->email);
        $result = $operadores->save();

        if ($result) {
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Datos guardador correctamente'
            ], Response::HTTP_OK);
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error en el servidor'
            ], Response::HTTP_OK);
        }

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Operadores $operadores
     * @return Response
     */
    public function destroy($id)
    {
        $operador = Operadores::find($id);
        if ($operador !== null) {
            $operador->delete();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'operador eliminado coreectamente'
            ]);
        }
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Error en el servidor'
        ], Response::HTTP_OK);
    }
}
