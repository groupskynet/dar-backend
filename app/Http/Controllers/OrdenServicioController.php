<?php

namespace App\Http\Controllers;

use App\Models\OrdenServicio;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrdenServicioController extends Controller
{

    public function index()
    {
        $ordenServicio = OrdenServicio::with('cliente', 'maquina', 'accesorios')->paginate(10);
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $ordenServicio
        ], Response::HTTP_OK);
    }

    public function all()
    {
        $ordenServicio = OrdenServicio::with('cliente', 'maquina', 'accesorio')->all();
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'success',
            'data' => $ordenServicio
        ], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'cliente' => 'numeric|required',
            'maquina' => 'numeric|required',
            'horometroInicial' => 'required',
            'horasPromedio' => 'required',
            'valorXhora' => 'required',
            'pagare' => 'required|mimes:pdf',
            'valorIda' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors());
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'invalid data'
            ], Response::HTTP_OK);
        }

        $path = '';

        if ($request->hasFile('pagare')) {
            $path = $request->file('pagare')->storeAs(
                'pagares', 'pagare-' . $request->cliente . '-' . date('Y-m-d-hh:mm') . '-' . $request->file('pagare')->getClientOriginalName()
            );
        }

        $ordenServicio = new OrdenServicio($request->all());
        $ordenServicio->pagare = $path;
        $result = $ordenServicio->save();

        if ($result) {
            $arr = isset($request->accesorios) ? $request->accesorios : [];
            $relations = [];
            foreach ($arr as $value) {
                $relations[$value['id']] = ['valorXhora' => $value['valor']];
            }
            $ordenServicio->accesorios()->sync($relations);
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'DAtos guardados correctamente'
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Error de servidor'
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        $orden = OrdenServicio::with('accesorios', 'maquina.accesorios', 'cliente')->where('id', $id)->first();
        if (!$orden)
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'Orden no encontrada'
            ], Response::HTTP_NOT_FOUND);

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Orden encontrada',
            'data' => $orden
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'cliente' => 'numeric|required',
            'maquina' => 'numeric|required',
            'horometroInicial' => 'required',
            'horasPromedio' => 'required',
            'valorXhora' => 'required',
            'valorIda' => 'required'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'invalid data',
                'data' => $request->all()
            ], Response::HTTP_OK);
        }

        $path = '';

        if ($request->hasFile('pagare')) {
            $path = $request->file('pagare')->storeAs(
                'pagares', 'pagare-' . $request->cliente . '-' . date('Y-m-d-hh:mm') . '-' . $request->file('pagare')->getClientOriginalName()
            );
        }

        $ordenServicio = OrdenServicio::find($id);
        $ordenServicio->fill($request->all());
        $ordenServicio->pagare = $path;
        $result = $ordenServicio->save();
        if ($result) {
            $arr = isset($request->accesorios) ? $request->accesorios : [];
            $relations = [];
            foreach ($arr as $value) {
                $relations[$value['id']] = ['valorXhora' => $value['valor']];
            }
            $ordenServicio->accesorios()->sync($relations);
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Datos gusrdados correctamente'
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Error de servidor'
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $ordenServicio = OrdenServicio::find($id);
        if ($ordenServicio) {
            $ordenServicio->delete();
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Orden de servicio eliminada correctamente'
            ]);
        }
        return response()->json([
            'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => 'Error del servidor'
        ], Response::HTTP_OK);
    }
}
