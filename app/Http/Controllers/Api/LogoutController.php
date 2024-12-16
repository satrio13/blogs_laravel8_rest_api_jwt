<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class LogoutController extends Controller
{
    function __invoke(Request $request)
    {        
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if($removeToken)
        {
            return response()->json(['status' => true, 'message' => 'Logout Berhasil!']);
        }
    }
    
}