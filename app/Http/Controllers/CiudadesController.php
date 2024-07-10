<?php

namespace App\Http\Controllers;

use App\Models\Ciudades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CiudadesController extends Controller{

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|unique:ciudades,nombre'
        ]);

        if ($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $ciudad = new Ciudades();
        $ciudad->nombre = $request->nombre;
        
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
