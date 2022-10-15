<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productos = Producto::with("categoria")->paginate(3);

        return response()->json($productos, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validamos
        $request->validate([
            "nombre" => "required",
            "categoria_id" => "required"
        ]);
        // subir imagen
        $nombre_imagen = "";
        if($file = $request->file("imagen")){
            $direccion_archivo = $file->getClientOriginalName();
            $file->move("imagenes/", $direccion_archivo);

            $nombre_imagen = "imagenes/". $direccion_archivo;

        }
        // guardamos
        $prod = new Producto();
        $prod->nombre = $request->nombre;
        $prod->precio = $request->precio;
        $prod->cantidad = $request->cantidad;
        $prod->descripcion = $request->descripcion;
        $prod->imagen = $nombre_imagen;
        $prod->categoria_id = $request->categoria_id;
        $prod->save();

        // respondemos

        return response()->json(["mensaje" => "Producto Registrado"], 201);
    }

    public function subirImagen(Request $request, $id)
    {
        $producto = Producto::find($id);
        
        if($file = $request->file("imagen")){
            $direccion_imagen = time()."-".$file->getClientOriginalName();
            $file->move("imagenes/", $direccion_imagen);           

            $producto->imagen = "imagenes/".$direccion_imagen;
            $producto->update();

        }

        return response()->json(["mensaje" => "Imagen Cargada"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prod = Producto::with('categoria')->find($id);
        // $prod->categoria;

        return response()->json($prod, 200);
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
         // validamos
         $request->validate([
            "nombre" => "required"
        ]);
        // subir imagen
        
        // guardamos
        $prod = Producto::find($id);
        $prod->nombre = $request->nombre;
        $prod->precio = $request->precio;
        $prod->cantidad = $request->cantidad;
        $prod->descripcion = $request->descripcion;

        if($file = $request->file("imagen")){
            $direccion_archivo = $file->getClientOriginalName();
            $file->move("imagenes/", $direccion_archivo);

            $nombre_imagen = "imagenes/". $direccion_archivo;

            $prod->imagen = $nombre_imagen;
        }

        $prod->categoria_id = $request->categoria_id;
        $prod->save();

        // respondemos

        return response()->json(["mensaje" => "Producto Modificado"], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        $producto->delete();
        return response()->json(["mensaje" => "Producto Eliminado"], 200);
    }
}
