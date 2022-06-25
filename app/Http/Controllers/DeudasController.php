<?php

namespace App\Http\Controllers;

use App\Models\Deudas;
use Illuminate\Http\Response;

class DeudasController extends Controller
{
   
    public function index()
    {
        $deudas = Deudas::with('mantenimiento.proveedor')->paginate(10);
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $deudas
        ], Response::HTTP_OK);
    }

    public function all()
    {
        $deudas = Deudas::with('mantenimiento.proveedor')->all();
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $deudas
        ], Response::HTTP_OK);
    }

   
}
