<?php

namespace App\Http\Controllers;

use App\Models\Supermercado;
use App\Models\Ciudades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class SupermercadoController extends Controller{

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:255',
            'NIT' => 'required|string|max:255|unique:supermercados,NIT',
            'Direccion' => 'required|string|max:255',
            'Logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'Longitud' => 'required|string|max:255',
            'Latitud' => 'required|string|max:255',
            'ID_ciudad' => 'required|exists:ciudades,ID_ciudad'
        ]);
    
        if ($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
    
        if ($request->hasFile('Logo')) {
            $logoPath = $request->file('Logo')->store('supermercados_logos', 'public');
        } else {
            $logoPath = null;
        }
    
        $supermercado = new Supermercado();
        $supermercado->Nombre = $request->Nombre;
        $supermercado->NIT = $request->NIT;
        $supermercado->Direccion = $request->Direccion;
        $supermercado->Logo = $logoPath;
        $supermercado->Longitud = $request->Longitud;
        $supermercado->Latitud = $request->Latitud;
        $supermercado->ID_ciudad = $request->ID_ciudad;
        
        if (!$supermercado->save()){
            $data = [
                'message' => 'Error al crear el supermercado',
                'status' => 500
            ];
            return response()->json($data, 500);
        }
    
        return response()->json($supermercado, 201);
    }
    

    public function index(): JsonResponse{
        $supermercados = Supermercado::with('ciudad')->get();
        
        if ($supermercados->isEmpty()) {
            $data = [
                'message' => 'No hay supermercados en la base de datos',
                'status' => 200
            ];
            return response()->json($data, 400);
        }
        $result = $supermercados->map(function ($supermercado) {
            return [
                'ID_supermercado' => $supermercado->ID_supermercado,
                'Nombre' => $supermercado->Nombre,
                'NIT' => $supermercado->NIT,
                'Direccion' => $supermercado->Direccion,
                'Logo' => $supermercado->Logo,
                'Longitud' => $supermercado->Longitud,
                'Latitud' => $supermercado->Latitud,
                'ciudad' => $supermercado->ciudad
            ];
        });
        return response()->json($result, 200);
    }

    public function update($id, Request $request) {
        try {
            $supermercado = Supermercado::find($id);
    
            if (!$supermercado) {
                $data = [
                    'message' => 'Supermercado no encontrado',
                    'status' => 404
                ];
                return response()->json($data, 404);
            }
    
            $validator = Validator::make($request->all(), [
                'Nombre' => 'required|string|max:255',
                'NIT' => 'required|string|max:255|unique:supermercados,NIT,' . $id . ',ID_supermercado',
                'Direccion' => 'required|string|max:255',
                'Latitud' => 'required|string|max:255',
                'Longitud' => 'required|string|max:255',
                'ID_ciudad' => 'required|exists:ciudades,ID_ciudad',
                'Logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
    
            if ($validator->fails()) {
                $data = [
                    'message' => 'Error en la validación de los datos',
                    'errors' => $validator->errors(),
                    'status' => 400
                ];
                return response()->json($data, 400);
            }
    
            $supermercado->Nombre = $request->input('Nombre');
            $supermercado->NIT = $request->input('NIT');
            $supermercado->Direccion = $request->input('Direccion');
            $supermercado->Latitud = $request->input('Latitud');
            $supermercado->Longitud = $request->input('Longitud');
            $supermercado->ID_ciudad = $request->input('ID_ciudad');
    
            if ($request->hasFile('Logo')) {
                // Elimina el logo anterior si existe
                if ($supermercado->Logo) {
                    Storage::disk('public')->delete($supermercado->Logo);
                }
                $logoPath = $request->file('Logo')->store('supermercados_logos', 'public');
                $supermercado->Logo = $logoPath;
            }
    
            $supermercado->save();
    
            $response = [
                'message' => 'Supermercado actualizado',
                'supermercado' => $supermercado,
                'status' => 200
            ];
            return response()->json($response, 200);
    
        } catch (\Exception $e) {
            $data = [
                'message' => 'Error al actualizar el supermercado',
                'error' => $e->getMessage(),
                'status' => 500
            ];
            return response()->json($data, 500);
        }
    }

    public function destroy($id){
        $supermercado = Supermercado::find($id);
        if (!$supermercado) {
            $data = [
                'message' => 'Supermercado no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $supermercado->delete();

        $data = [
            'message' => 'Supermercado eliminado correctamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function buscarPorCiudad($nombreCiudad){
        
        $ciudad = Ciudades::where('Nombre', $nombreCiudad)->first();

        if (!$ciudad) {
            $data = [
                'message' => 'La ciudad especificada no fue encontrada en la base de datos',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $supermercados = Supermercado::where('ID_ciudad', $ciudad->ID_ciudad)->get();

        if ($supermercados->isEmpty()) {
            $data = [
                'message' => 'No se encontraron supermercados para la ciudad especificada',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        $data = [
            'supermercados' => $supermercados,
            'ciudad' => $ciudad,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

        
    }

