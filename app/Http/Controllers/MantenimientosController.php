<?php

namespace App\Http\Controllers;

use App\Models\Mantenimientos;
use App\Rules\HorometroMaquinaRule;
use App\Rules\TicketsPendientesRule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Services\FactoryPago as ServicesFactoryPago;

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
            'horometro' => ['numeric', 'required', new HorometroMaquinaRule($request->maquina)],
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
        try {
            DB::beginTransaction();
            $mantenimiento = new Mantenimientos($request->all());
            $mantenimiento->soporte = $path;
            $mantenimiento->descripcion = strtoupper($request->descripcion);
            $mantenimiento->save();
            $request->mantenimiento = $mantenimiento->id;
            $factory = new ServicesFactoryPago($request->modalidad);
            $pago = $factory->create();
            $pago->pago($request);
            DB::commit();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Datos guardados correctamente',
                'data' => $mantenimiento
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error de servidor',
                'error' => $e->getMessage()
            ], Response::HTTP_OK);
        }
    }

    public function destroy(Mantenimientos $mantenimiento)
    {
        //
    }
}