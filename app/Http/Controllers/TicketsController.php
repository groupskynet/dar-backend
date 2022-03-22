<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets=Tickets::with('cliente','maquina','accesorio')->paginate(10);
        return response()->json([
            'status'=>Response::HTTP_OK,
            'message'=>'success',
            'data'=>$tickets
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
            'cliente'=>'numeric|required',
            'fecha'=>'required',
            'nOrden'=>'required',
            'maquina'=>'numeric|required',
            'accesorio'=>'numeric',
            'horometroInicial'=>'required',
            'horometroFinal'=>'required'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
            return response()->json([
                'status'=>Response::HTTP_BAD_REQUEST,
                'message'=>'invalid data'
            ],Response::HTTP_OK);
        }

        $tickets = new Tickets($request->all());
        $result = $tickets->save();
        if($result){
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'Datos guardados correctamente'
            ],Response::HTTP_OK);
        }
        return response()->json([
            'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'=>'Erros del servidor'
        ],Response::HTTP_OK);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tickets  $tickets
     * @return \Illuminate\Http\Response
     */
    public function show(Tickets $tickets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tickets  $tickets
     * @return \Illuminate\Http\Response
     */
    public function edit(Tickets $tickets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tickets  $tickets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
          $validate = Validator::make($request->all(),[
            'cliente'=>'numeric|required',
            'fecha'=>'required',
            'nOrden'=>'required',
            'maquina'=>'numeric|required',
            'accesorio'=>'numeric',
            'horometroInicial'=>'required',
            'horometroFinal'=>'required'
        ]);
        if($validate->fails()){
            return response()->json($validate->errors());
            return response()->json([
                'status'=>Response::HTTP_BAD_REQUEST,
                'message'=>'invalid data'
            ],Response::HTTP_OK);
        }

        $tickets = Tickets::find($id);
        $tickets->fill($request->all());
        $result = $tickets->save();

        if($result){
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'Datos guardados correctamente'
            ],Response::HTTP_OK);
        }
        return response()->json([
            'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'=>'Erros del servidor'
        ],Response::HTTP_OK);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tickets  $tickets
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket =Tickets::find($id);
        if ($ticket){
            $ticket->delete();
            return response()->json([
                'status'=>Response::HTTP_OK,
                'message'=>'Ticket eliminado correctamente'
            ]);
        }
        return response()->json([
            'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
            'message'=>'Error del servidor'
        ],Response::HTTP_OK);
    }
}
