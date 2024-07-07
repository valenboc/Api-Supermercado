<?php

use Illuminate\Support\Facades\Route;

Route::get('/ciudades/all', 'App\Http\Controllers\CiudadesController@index');


Route::get('/supermercados/all', 'App\Http\Controllers\SupermercadoController@index');
Route::post('/supermercado/new', 'App\Http\Controllers\SupermercadoController@store');
Route::get('/supermercado/porCiudad/{nombreCiudad}', 'App\Http\Controllers\SupermercadoController@buscarPorCiudad');
Route::put('/supermercado/update/{id}', 'App\Http\Controllers\SupermercadoController@update');
Route::delete('/supermercado/delete/{id}', 'App\Http\Controllers\SupermercadoController@destroy');




