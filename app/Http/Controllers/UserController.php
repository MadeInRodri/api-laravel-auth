<?php

namespace App\Http\Controllers;

use Error;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    //Controlador de registro:
    public function store(Request $request)
    {
        try{
            //Bail para que se detenga la validación una vez se detecte el error en ese campo
            $validatedData = Validator::make($request->all(),[
            'name' => 'bail|required|string|min:3',
            'email' => 'bail|required|string|email|unique:users',
            'role' => 'nullable|string|in:admin,empleado',
            'password' => 'required|string|min:8'
            ], 
        ['email.unique' => 'El email ya existe']);

            if($validatedData->fails()){

                return response()->json([
                    'status' => "error",
                    'mensaje' => "Credenciales de usuario invalidas",
                    'errores' => $validatedData->errors()
                ]);
            }
            $newUser =  $validatedData->getData();
            $newUser['role'] = $newUser['role'] ?? 'empleado';
            
            $newUser['password'] = Hash::make($newUser['password']);

            $user = User::create($newUser);

            return response()->json([
                'status' => "exito",
                'mensaje' => 'Usuario creado existosamente',
                'user' => $user
            ],201);
        } catch (Exception $e) {
            return response()->json([
                'status' => "error",
                'mensaje' => 'se cayo el porton',
                'error' => $e->getMessage()
            ]);
        }
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
        //Investigar por que hace esto el $user -> id
        $validatedData = Validator::make($request->all(),[
            'name' => 'sometimes|string|min:3',
            'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
            'role' => 'sometimes|string|in:admin,empleado',
            'password' => 'sometimes|string|min:8'
        ],
        ['email.unique' => 'El email ya existe']
        );

        if($validatedData->fails()){
            return response()->json([
                'status' => "error",
                'mensaje' => "Credenciales invalidas",
                'errores' => $validatedData->errors()
            ]);
        }
        $newUser = $validatedData ->getData();

        if(isset($newUser['password'])){
           $newUser['password'] = Hash::make($newUser['password']);
        }

        $user->update($newUser);

        return response()->json([
        'status' => 'exito',    
        'message' => 'El usuario ha sido actualizado', 
        'usuario' => $user],200
        );

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

    public function login(Request $request){

        //Este coso no devuelve un vector, devuelve el validator xd ->Parecido a Validators
        $validatedData =  Validator::make($request->all(), 
        [
            'email'=> 'bail|required|email',
            'password' => 'sometimes|string|min:8'
        ]);

        if($validatedData->fails()){
            return response()->json([
                "status" => 'error',
                'mensaje'=>'Credenciales invalidas',
                'error' => $validatedData->errors()
            ]);
        }

        $user = User::where('email', $validatedData->getValue('email'))->first();
     

        if(!$user || !Hash::check($validatedData->getValue('password'), $user->password)){
            return response()->json([
                'status' => 'error',
                'mensaje' => 'Correo o contraseña invalidos'
            ]);
        }

        
        //Creo el token....
        try{
            if(!$token =  JWTAuth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => 'error',
                    'mensaje' => 'Token no creado. Credenciales invalidas'
                ]);
            }
            return response()->json([
                'status' => 'exito',
                'mensaje' => 'Usuario logeado.',
                //Arreglar esto: Mandarlo de una manera más segura. 
                'token' => $token
            ]);
        }catch(JWTException $e){
            return response()->json(['error' =>"SE CAYO EL PORTON"]);
        }


    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['status'=> 'exito',
        'mensaje' => 'Usuario deslogeado. Eliminado token...',
        ]);
    }
}