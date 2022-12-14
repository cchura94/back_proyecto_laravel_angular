<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pedidos = Pedido::with('cliente', 'productos')->get();

        return response()->json($pedidos, 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
        {
            cliente_id,
            productos: [
                {id: 1, cantidad: 2},
                {id: 5, cantidad: 1}
            ]
        }
        */
        $request->validate([
            "cliente_id" => "required"
        ]);
        
        DB::beginTransaction();

        try {

            $pedido = new Pedido();
            $pedido->fecha_pedido = date("Y-m-d H:i:s");
            $pedido->cliente_id = $request->cliente_id;
            $pedido->save();

            // agregar los productos a este pedido
            foreach ($request->productos as $prod) {
                $pedido->productos()->attach($prod["id"], ["cantidad" => $prod["cantidad"]]);               
            }

            $pedido->estado = 2;
            $pedido->update();

            
            DB::commit();

            return response(["mensaje" => "Pedido registrado"], 201);

        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return response(["mensaje" => "Error al registrar el pedido", "error" => $e], 422);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pedido = Pedido::with('cliente', 'productos')->findOrFail($id);
        
        return response($pedido, 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
