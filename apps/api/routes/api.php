<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\VeiculoController;
use App\Http\Controllers\Api\VeiculoImagemController;

Route::get('health', fn () => response()->json([
    'ok'   => true,
    'time' => now()->toIso8601String(),
]));

Route::prefix('auth')->group(function () {
    Route::post('registrar', [AuthController::class, 'register']);//->middleware('throttle:register');
    Route::post('entrar',    [AuthController::class, 'login']);//->middleware('throttle:login');
    Route::post('sair',      [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('me',         [AuthController::class, 'me'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('veiculos', VeiculoController::class);

    Route::post('veiculos/{veiculo}/imagens', [VeiculoImagemController::class, 'enviar'])
    ->middleware('throttle:uploads')
    ->name('veiculos.imagens.enviar');

    Route::patch('veiculos/{veiculo}/imagens/{imagem}/capa', [VeiculoImagemController::class, 'definirCapa'])
    ->middleware('throttle:uploads')
    ->name('veiculos.imagens.capa');

    Route::delete('veiculos/{veiculo}/imagens/{imagem}', [VeiculoImagemController::class, 'excluir'])
    ->name('veiculos.imagens.excluir');
});