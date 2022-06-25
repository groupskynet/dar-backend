<?php

namespace App\Http\Controllers;

use App\Models\Maquinas;
use App\Models\OrdenServicio;
use App\Models\Tickets;
use App\Rules\AccesorioTicketRule;
use App\Rules\FacturaGasolinaTicketRule;
use App\Rules\TicketsPosterioresAlaFechaRule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TicketsController extends Controller
{

    public function index()
    {
        $tickets = Tickets::with('operador', 'cliente', 'maquina', 'accesorio')->paginate(10);
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $tickets
        ], Response::HTTP_OK);
    }

    public function all()
    {
        $tickets = Tickets::all();
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $tickets
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'cliente' => 'numeric|required',
            'maquina' => 'numeric|required',
            'fecha' => ['required', 'date', 'before:tomorrow', new TicketsPosterioresAlaFechaRule($request->maquina)],
            'nOrden' => 'required',
            'operador' => 'required',
            'accesorio' => [new AccesorioTicketRule($request->maquina, $request->fecha)],
            'horometroInicial' => 'required',
            'horometroFinal' => 'required',
            'tieneCombustible' => 'required',
            'soporte' => 'required|mimes:png,jpg,jpeg|max:1000',
            'factura' => [new FacturaGasolinaTicketRule($request->tieneCombustible)],
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $validate->errors()->first(),
                'data' => $validate->errors()
            ], Response::HTTP_OK);
        }

        $path = '';

        if ($request->hasFile('soporte')) {
            $path = $request->file('soporte')->storeAs(
                'soportes/tickets',
                'ticket-' . date('Y-m-d-hh:mm:ss') . '-' . $request->file('soporte')->getClientOriginalName()
            );
        }

        if ($request->tieneCombustible && $request->hasFile('factura')) {
            $path = $request->file('factura')->storeAs(
                'soportes/combustible',
                'factura-' . date('Y-m-d-hh:mm:ss') . '-' . $request->file('soporte')->getClientOriginalName()
            );
        }

        $ticket = new Tickets($request->all());
        $ticket->orden = $request->nOrden;
        $ticket->soporte = $path;
        $result = $ticket->save();
        if ($result) {
            $ticket = Tickets::with('operador', 'maquina', 'accesorio', 'cliente')
                ->where('id', $ticket->id)
                ->first();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Datos guardados correctamente',
                'data' => $ticket
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Error del servidor'
        ], Response::HTTP_OK);
    }

    public function update($id)
    {
        try {
            DB::beginTransaction();
            $ticket = Tickets::find($id);
            if ($ticket === null) {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => 'El ticket no fue confirmado, por favor intentelo nuevamente'
                ]);
            }
            $ticket->estado = 'CONFIRMADO';
            $ticket->save();
            $maquina = Maquinas::find($ticket->maquina);
            $maquina->horometro = $ticket->horometroFinal;
            $maquina->save();
            DB::commit();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Ticket confirmado correctamente',
                'data' => $ticket
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error del servidor',
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $ticket = Tickets::find($id);
            if ($ticket) {
                $aux = $ticket;
                $ticket->delete();
                $tickets = Tickets::where([['orden', $ticket->orden], ['fecha', '>', $ticket->fecha]])->get();
                foreach ($tickets as $item) {
                    $item->delete();
                }
                $ticket = Tickets::where([['orden', $ticket->orden], ['estado', 'CONFIRMADO']])->orderBy('fecha')->get()->last();
                $maquina = Maquinas::findOrFail($aux->maquina);
                if ($ticket) {
                    $maquina->horometro = $ticket->horometroFinal;
                    $maquina->save();
                } else {
                    $orden = OrdenServicio::find($aux->orden);
                    $maquina->horometro = $orden->horometroInicial;
                    $maquina->save();
                }
            }
            DB::commit();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Ticket eliminado correctamente',
                'data' => Tickets::with('operador', 'cliente', 'maquina', 'accesorio')->paginate(10)
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Error del servidor'
            ], Response::HTTP_OK);
        }
    }
}
