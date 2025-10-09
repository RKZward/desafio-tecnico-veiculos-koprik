<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    VeiculoController,
    VeiculoImagemController
};


// Route::get('/health', fn () => ['ok' => true, 'ts' => now()]);


Route::prefix('auth')->group(function () {
    Route::post('registrar', [AuthController::class, 'register']);
    Route::post('entrar',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('eu',    [AuthController::class, 'eu']);
        Route::post('sair', [AuthController::class, 'logout']);
    });
});


Route::middleware('auth:sanctum')->group(function () {

    // Veículos
    Route::apiResource('veiculos', VeiculoController::class);
    Route::get   ('/veiculos',                  [VeiculoController::class, 'index']);
    Route::post  ('/veiculos',                  [VeiculoController::class, 'store']);
    Route::get   ('/veiculos/{id}',             [VeiculoController::class, 'show']);
    Route::match (['put','patch'],'/veiculos/{id}', [VeiculoController::class, 'update']);
    Route::delete('/veiculos/{id}',             [VeiculoController::class, 'destroy']);

    // Imagens do veículo
    Route::post  ('/veiculos/{veiculoId}/imagens',                    [VeiculoImagemController::class, 'enviar']);
    Route::patch ('/veiculos/{veiculoId}/imagens/{imagemId}/capa',    [VeiculoImagemController::class, 'definirCapa']);
    Route::delete('/veiculos/{veiculoId}/imagens/{imagemId}',         [VeiculoImagemController::class, 'excluir']);
});
