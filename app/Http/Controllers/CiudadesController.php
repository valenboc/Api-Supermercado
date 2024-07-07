<?php

namespace App\Http\Controllers;

use App\Models\Ciudades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CiudadesController extends Controller{

    public function store(Request $request){
        // Validación de la solicitud
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:ciudades,nombre'
        ]);

        // Si la validación falla, se devuelve un mensaje de error
        if ($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        // Creación de una nueva instancia del modelo Ciudades
        $ciudad = new Ciudades();
        $ciudad->nombre = $request->nombre;
        
        // Guardar la ciudad en la base de datos
        if (!$ciudad->save()){
            $data = [
                'message' => 'Error al crear una ciudad',
                'status' => 500
            ];
            return response()->json($data, 500);
        }
        $data = [
            'message' => 'Ciudad creada correctamente',
            'ciudad' => $ciudad
        ];
        // Si la creación es exitosa, se devuelve la nueva ciudad con un estado HTTP 201
        return response()->json($data, 201);
    }

    public function index(){
        $ciudades = Ciudades::all();
        if ($ciudades->isEmpty()){
            $data = [
                'message' => 'No hay ciudades en la base de datos',
                'status' => 200
            ];
            return Response()->json($data, 400);
        }
        return Response()->json($ciudades, 200);
    }

}
