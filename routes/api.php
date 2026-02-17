<?php

Use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsUserAdmin;
Use App\Http\Middleware\IsUserAuth;

/* Route::get('/user', function (Request $request) {
    return $request->user();
    //Este Middleware hace algo xd
})->middleware('auth:sanctum');
 */

//Una vez registrado:
Route::middleware([isUserAuth::class])->group(function (){
    Route::get('/usuarios',[UserController::class,'index']);

    Route::get('/usuarios/{id}',[UserController::class,'show']);

    Route::get('/logout', [UserController::class, 'logout']);

    //Route::patch('/usuarios/{id}',[UserController::class,'update']);
    //Para acceder a estas dos rutas se tiene que verificar que tenga: El Token JWT y que en la petición venga el role.
    Route::middleware([IsUserAdmin::class])->group(function (){
        //Que solo admin haga esto
        Route::delete('/usuarios/{id}',[UserController::class,'destroy']);
        //Que solo admin haga esto
        Route::patch('/usuarios/{id}',[UserController::class,'update']);
    });
});




//----------Entrada de API
Route::post('/registro', [UserController::class,'store']);

Route::post('/login', [UserController::class,'login']);