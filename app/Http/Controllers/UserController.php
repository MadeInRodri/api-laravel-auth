<?php

namespace App\Http\Controllers;

use Error;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        //
        // $users = User::all();

        $query = User::query();

        if($request->has('role')){
            $query->where('role',$request->role);
        }

        $users = $query->get();

        if($users->isEmpty()){
            return response()->json(['Message' => 'No hay usuarios registrados'],404);
        }
        return response()->json($users,200);
    }


    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|string|email|unique:users',
            'role' => 'nullable|string|in:admin,employee',
            'password' => 'required|string|min:8'
        ]);

        $validatedData['role'] = $validatedData['role'] ?? 'employee';
        
        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Usuario creado existosamente',
            'user' => $user
        ],201);
    }

    public function show(string $id)
    {

        try {
        $user = User::findOrFail($id);
        return response()->json($user, 200);
        } 
        catch (ModelNotFoundException $e) { 
            return response()->json([
                'status' => 'error',
                'message' => 'No existe un usuario con el ID: ' . $id
            ], 404);

    } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Ocurrió un error en el servidor'
        ], 500);
    }
    }


    public function update(Request $request, string $id)
    {
        //
        $user = User::find($id);

        if(!$user){
            return response()->json(['message'=>'El usuario no existe'],404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|min:3',
            'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
            'role' => 'sometimes|string|in:admin,employee',
            'password' => 'sometimes|string|min:8'
        ]);

        if(isset($validatedData['password'])){
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json(['message' => 'El usuario ha sido actualizado', 'user' => $user],200);

    }


    public function destroy(string $id)
    {
        //
        $user = User::find($id);
        if(!$user){
            return response()->json(['Message'=>'No existe este usuario'],400);
        }
        $user->delete();
        return response()->json(['Message' => 'El usuario ha sido eliminado correctamente'],200);
    }
}