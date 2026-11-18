<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class studentController extends Controller
{
    //
    public function index(){
        $students = Student::all();
        
        if($students->isEmpty()){
           $data= [
            'message' => 'No se encontraron estudiantes',
            'status' => 200
           ]; 
           return response()->json($data, 200);
        }
        return response()->json($students, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:20',
            'languaje' => 'required|string|max:50',
        ]);

        if($validator->fails()){
            $data =[
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400 
            ];
            return response()->json($data, 400);
        }

        $student = Student::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'languaje' => $request->input('languaje')
        ]); 

        if(!$student){
            $data = [
                'message' => 'Error al crear el estudiante',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Estudiante creado exitosamente',
            'student' => $student,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($id){
        $student = Student::find($id);
        if(!$student){
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $data = [
            'message' => 'Estudiante encontrado',
            'student' => $student,
            'status' => 200
        ];
        return response()->json($data, 200);
    }

    public function destroy($id){
            $student = Student::find($id);
            if(!$student){
                $data = [
                    'message' => 'Estudiante no encontrado',
                    'status' => 404
                ];
                return response()->json($data, 404);
            }

            $student->delete();

            $data = [
                'message' => 'Estudiante eliminado exitosamente',
                'status' => 200
            ];
            return response()->json($data, 200);
    }

    public function update(Request $request, $id){

        $student = Student::find($id);

        if(!$student){
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:students,email,'.$student->id,
            'phone' => 'sometimes|required|string|max:20',
            'languaje' => 'sometimes|required|string|max:50',
        ]);

        if($validator->fails()){
            $data =[
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400 
            ];
            return response()->json($data, 400);
        }

        $student->update($request->only(['name', 'email', 'phone', 'languaje']));
        $student->save();

        $data = [
            'message' => 'Estudiante actualizado exitosamente',
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function updatePartial(Request $request, $id){

        $student = Student::find($id);
        if(!$student){
            $data = [
                'message' => 'Estudiante no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:students,email,'.$student->id,
            'phone' => 'sometimes|required|string|max:20',
            'languaje' => 'sometimes|required|string|max:50',
        ]);

        if($validator->fails()){
            $data =[
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
                'status' => 400 
            ];
            return response()->json($data, 400);
        }

        if($request->has('name')){
            $student->name = $request->input('name');
        }
        if($request->has('email')){
            $student->email = $request->input('email');
        }
        if($request->has('phone')){
            $student->phone = $request->input('phone');
        }
        if($request->has('languaje')){
            $student->languaje = $request->input('languaje');
        }

        $student->save();

        $data = [
            'message' => 'Estudiante actualizado exitosamente',
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
