<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Mando a llamar a configuración (llamada api), para que me valide las rutas con el prefijo user :3
        if(auth('api')->user()){
            return $next($request);
        }else{
            return response()->json([
                'status' =>'error',
                'mensaje' => 'Usuario no autorizado'
            ]);
        }
    }
}
