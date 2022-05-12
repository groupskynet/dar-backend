<?php

namespace App\Http\Controllers;

use App\Models\Mantenimientos;
use App\Rules\TicketsPendientesRule;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use services\FactoryPago as ServicesFactoryPago;

class MantenimientosController extends Controller
{

    public function index()
    {
        $mantenimientos = Mantenimientos::with('proveedor', 'maquina')->paginate(10);
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $mantenimientos
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'tipo' => 'required',
            'maquina' => ['numeric', 'required', new TicketsPendientesRule()],
            'proveedor' => 'numeric|required',
            'descripcion' => 'required',
            'horometro' => 'numeric|required',
            'modalidad' => 'required',
            'costo' => 'required',
            'soporte' => 'required|mimes:pdf'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'invalid data',
                'data' => $validate->errors()
            ], Response::HTTP_OK);
        }

        $path = '';

        if ($request->hasFile('soporte')) {
            $path = $request->file('soporte')->storeAs(
                'soportes',
                'soporte-' . $request->proveedor . '-' . date('Y-m-d-hh:mm') . '-' . $request->file('soporte')->getClientOriginalName()
            );
        }

        $mantenimiento = new Mantenimientos($request->all());
        $mantenimiento->soporte = $path;
        $mantenimiento->descripcion = strtoupper($request->descripcion);
        $resullt = $mantenimiento->save();

        if ($resullt) {
            $request->mantenimiento = $mantenimiento->id;
            $factory = new ServicesFactoryPago($request->modalidad);
            $pago = $factory->create();
            $pago->pago($request);
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

    public function destroy(Mantenimientos $mantenimiento)
    {
        //
    }
}
