<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    function __invoke(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed'
            ]);

            if($validator->fails())
            {
                return response()->json($validator->errors(), 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response()->json(['status' => true, 'message' => 'Berhasil register', 'user' => $user], 201);
        }catch(\Exception $e)
        {
            return response()->json(['status' => false], 409);
        }
    }

}