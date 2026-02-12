<?php

Use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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
    //Que solo admin haga esto
    Route::delete('/usuarios/{id}',[UserController::class,'destroy']);
    //Que solo admin haga esto
    Route::patch('/usuarios/{id}',[UserController::class,'update']);

    Route::get('/logout', [UserController::class, 'logout']);
});




//----------Entrada de API
Route::post('/registro', [UserController::class,'store']);

Route::post('/login', [UserController::class,'login']);

