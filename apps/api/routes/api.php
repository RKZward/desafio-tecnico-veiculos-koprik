<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    VeiculoController,
    VeiculoImagemController
};

Route::prefix('auth')->group(function () {
    Route::post('registrar', [AuthController::class, 'register']);
    Route::post('entrar',    [AuthController::class, 'login']);
    Route::post('sair',      [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware(['auth:sanctum','throttle:uploads'])->group(function () {
    // CRUD RESTful (gera index, store, show, update, destroy)
    Route::apiResource('veiculos', VeiculoController::class);

    // Imagens do veículo (usa binding de {veiculo} e {imagem})
    Route::post   ('veiculos/{veiculo}/imagens',               [VeiculoImagemController::class, 'enviar']);
    Route::patch  ('veiculos/{veiculo}/imagens/{imagem}/capa', [VeiculoImagemController::class, 'definirCapa']);
    Route::delete ('veiculos/{veiculo}/imagens/{imagem}',      [VeiculoImagemController::class, 'excluir']);
});
