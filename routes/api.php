<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => "v1/auth"], function(){

    
    Route::post("login", [AuthController::class, "ingresar"]);
    Route::post("registro", [AuthController::class, "registrar"]);
    
    Route::group(['middleware' => 'auth:sanctum'], function(){
        
        Route::get("perfil", [AuthController::class, "perfil"]);
        Route::post("logout", [AuthController::class, "salir"]);
    });
});

Route::group(['middleware' => 'auth:sanctum'], function(){

    Route::apiResource("categoria", CategoriaController::class);
    Route::apiResource("cliente", ClienteController::class);
    Route::apiResource("producto", ProductoController::class);
    Route::apiResource("pedido", PedidoController::class);

});

Route::get("/no-autorizado", function(){
    return response()->json(["mensaje" => "no Autorizado"], 401);
})->name("login");
