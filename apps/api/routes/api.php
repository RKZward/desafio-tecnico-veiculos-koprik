<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController,VehicleController,VehicleImageController};

Route::get('/health', fn () => ['ok' => true, 'ts' => now()]);



Route::prefix('auth')->group(function () {
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',   [AuthController::class,'login']);
    Route::middleware('auth:sanctum')->group(function(){
      Route::get('me',     [AuthController::class,'me']);
      Route::post('logout',[AuthController::class,'logout']);
    });
  });
  
  Route::middleware('auth:sanctum')->group(function () {
    Route::get('/vehicles',                [VehicleController::class,'index']);
    Route::post('/vehicles',               [VehicleController::class,'store']);
    Route::get('/vehicles/{id}',           [VehicleController::class,'show']);
    Route::match(['put','patch'],'/vehicles/{id}', [VehicleController::class,'update']);
    Route::delete('/vehicles/{id}',        [VehicleController::class,'destroy']);
  
    Route::post('/vehicles/{vehicleId}/images',                    [VehicleImageController::class,'store']);
    Route::patch('/vehicles/{vehicleId}/images/{imageId}/cover',   [VehicleImageController::class,'setCover']);
    Route::delete('/vehicles/{vehicleId}/images/{imageId}',        [VehicleImageController::class,'destroy']);
  });