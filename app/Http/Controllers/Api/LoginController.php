<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required'
        ]);
        
        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if(!$token = JWTAuth::attempt($credentials))
        {
            return response()->json(['status' => false, 'message' => 'Email atau Password Anda salah'], 401);
        }else
        {
            return response()->json(['status' => true, 'user' => auth()->user(), 'token' => $token]);
        }
    }

}