<?php

namespace App\Http\Controllers;

use App\Models\Supermercado;
use App\Models\Ciudades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class SupermercadoController extends Controller{

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'Nombre' => 'required|string|max:255',
            'NIT' => 'required|string|max:255|unique:supermercados,NIT',
            'Direccion' => 'required|string|max:255',
            'Logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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

        $logoPath = $request->file('Logo')->store('supermercados_logos', 'public');

        $supermercado = new Supermercado();
        $supermercado->Nombre = $request->Nombre;
        $supermercado->NIT = $request->NIT;
        $supermercado->Direccion = $request->Direccion;
        $supermercado->Logo =  $logoPath;
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

    public function update($id, Request $request){
        // Busca el supermercado usando la clave primaria personalizada
        $supermercado = Supermercado::find($id);

        if (!$supermercado) {
            $data = [
                'message' => 'Supermercado no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'Nombre' => 'required',
            'NIT' => 'required|unique:supermercados,NIT,' . $id . ',ID_supermercado',
            'Direccion' => 'required',
            'Logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Latitud' => 'required',
            'Longitud' => 'required',
            'ID_ciudad' => 'required|exists:ciudades,ID_ciudad'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $supermercado->Nombre = $request->Nombre;
        $supermercado->NIT = $request->NIT;
        $supermercado->Direccion = $request->Direccion;
        $supermercado->Logo = $request->Logo;
        $supermercado->Latitud = $request->Latitud;
        $supermercado->Longitud = $request->Longitud;
        $supermercado->ID_ciudad = $request->ID_ciudad;

        $supermercado->save();

        $data = [
            'message' => 'Supermercado actualizado',
            'supermercado' => $supermercado,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function destroy($id){
        // Busca el supermercado por su ID
        $supermercado = Supermercado::find($id);

        // Si no se encuentra el supermercado, devuelve un mensaje de error
        if (!$supermercado) {
            $data = [
                'message' => 'Supermercado no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        // Elimina el supermercado
        $supermercado->delete();

        // Prepara y devuelve la respuesta JSON
        $data = [
            'message' => 'Supermercado eliminado correctamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function buscarPorCiudad($nombreCiudad){
        
        // Buscar la ciudad por su nombre
        $ciudad = Ciudades::where('Nombre', $nombreCiudad)->first();

        // Verificar si la ciudad existe
        if (!$ciudad) {
            $data = [
                'message' => 'La ciudad especificada no fue encontrada en la base de datos',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        // Obtener todos los supermercados que pertenecen a la ciudad encontrada
        $supermercados = Supermercado::where('ID_ciudad', $ciudad->ID_ciudad)->get();

        // Verificar si se encontraron supermercados para la ciudad especificada
        if ($supermercados->isEmpty()) {
            $data = [
                'message' => 'No se encontraron supermercados para la ciudad especificada',
                'status' => 200
            ];
            return response()->json($data, 200);
        }

        // Preparar la respuesta JSON con la información requerida
        $data = [
            'supermercados' => $supermercados,
            'ciudad' => $ciudad,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

        
    }

