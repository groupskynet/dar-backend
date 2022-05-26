<?php

namespace App\Http\Controllers;

use App\Models\Abonos;
use Illuminate\Http\Response;

class AbonosController extends Controller
{
    
    public function index()
    {
        $abonos = Abonos::with('proveedor')->paginate(10);
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $abonos
        ], Response::HTTP_OK);
    }

    public function all()
    {
        $abonos = Abonos::with('proveedor')->all(); 
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $abonos
        ], Response::HTTP_OK);
    }
}
