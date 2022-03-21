<?php

namespace App\Http\Controllers;

use App\Models\OrdenServicio;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrdenServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ordenServicio = OrdenServicio::with('cliente','maquina')->get();
        return response()->json([
            'status'=> Response::HTTP_OK,
            'message'=>'success',
            'data'=> $ordenServicio
        ],Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'cliente'=> 'numeric|required',
            'maquina'=> 'numeric|required',
            'horometroInicial'=>'required',
            'horasPromedio'=>'required',
            'valorXhora'=>'required',
            'pagare'=>'required|mimes:pdf',
            'valorIda'=>'required'
        ]);

        if($validate->fails()){
            return response()->json($validate->errors());
            return response()->json([
                'status'=>Response::HTTP_BAD_REQUEST,
                'message'=>'invalid data'
            ],Response::HTTP_OK);
        }

        $path = '';

        if($request->hasFile('pagare')){
            $path = $request->file('pagare')->storeAs(
                'pagares','pagare-'.$request->cliente.'-'. date('Y-m-d-hh:mm').'-'.$request->file('pagare')->getClientOriginalName()
            );
        }

        $ordenServicio = new OrdenServicio($request->all());
        $ordenServicio->pagare = $path;
        $result = $ordenServicio->save();

        if($result){
            return response()->json([
                'status'=> Response::HTTP_OK,
                'message'=> 'DAtos guardados correctamente'
            ],Response::HTTP_OK);
        }
        return response()->json([
            'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'=> 'Error de servidor'
        ],Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OrdenServicio  $ordenServicio
     * @return \Illuminate\Http\Response
     */
    public function show(OrdenServicio $ordenServicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OrdenServicio  $ordenServicio
     * @return \Illuminate\Http\Response
     */
    public function edit(OrdenServicio $ordenServicio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OrdenServicio  $ordenServicio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(),[
            'cliente'=>'numeric|required',
            'maquina'=>'numeric|required',
            'horometroInicial'=>'required',
            'horasPromedio'=>'required',
            'valorXhora'=>'required',
            'pagare'=>'required|mimes:pdf',
            'valorIda'=>'required'
        ]);
        if($validate->fails()){
            return response()->json([
                'status'=>Response::HTTP_BAD_REQUEST,
                'message'=>'invalid data'
            ],Response::HTTP_OK);
        }

        $ordenServicio =OrdenServicio::find($id);
        $ordenServicio->fill($request->all());
        $result=$ordenServicio->save();
        if($result){
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'Datos gusrdados correctamente'
            ],Response::HTTP_OK);
        }
        return response()->json([
            'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'=>'Error de servidor'
        ],Response::HTTP_OK);   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrdenServicio  $ordenServicio
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ordenServicio=OrdenServicio::find($id);
        if($ordenServicio){
            $ordenServicio->delete();
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'Orden de servicio eliminada correctamente'
            ]);
        }   
        return response()->json([
            'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'=>'Error del servidor'
        ],Response::HTTP_OK);
    }
}
