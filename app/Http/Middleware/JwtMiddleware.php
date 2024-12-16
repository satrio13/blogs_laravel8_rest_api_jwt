<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try 
        {
            JWTAuth::parseToken()->authenticate(); // Cek token JWT
        }catch(Exception $e) 
        {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) 
            {
                return response()->json(['status' => 'error', 'message' => 'Token is Invalid'], 401);
            }elseif($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException)
            {
                return response()->json(['status' => 'error', 'message' => 'Token is Expired'], 401);
            }else
            {
                return response()->json(['status' => 'error', 'message' => 'Authorization Token not found'], 401);
            }
        }

        return $next($request);
    }

}