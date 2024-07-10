<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperMercadoController;
use App\Http\Controllers\CiudadesController;

//Rutas con auth required para ciudades
Route::middleware('auth:api')->get('/ciudades/all', [CiudadesController::class, 'index']);

//Rutas con auth required para supermercados
Route::middleware('auth:api')->group(function () {
    Route::get('/supermercados/all', [SuperMercadoController::class, 'index']);
    Route::post('/supermercado/new', [SuperMercadoController::class, 'store']);
    Route::get('/supermercado/porCiudad/{nombreCiudad}', [SuperMercadoController::class, 'buscarPorCiudad']);
    Route::post('/supermercado/update/{id}', [SuperMercadoController::class, 'update']);
    Route::delete('/supermercado/delete/{id}', [SuperMercadoController::class, 'destroy']);
});

//Rutas para la auth
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/user/register', [AuthController::class, 'register'])->name('register');
    Route::post('/user/login', [AuthController::class, 'login'])->name('login');
    Route::post('/user/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});

