<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        //set validasi
        $validator = Validator::make($request->all(),[
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        //response error validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //get "email" dan "password" dari input
        $credentials = $request->only('email', 'password');

        //check jika "email" dan "password" tidak sesuai
        if(!$token = auth()->guard('api')->attempt($credentials))
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 400);

        $user = auth()->guard('api')->user();

        // response login "success" dengan generate "Token"
        return response()->json([
            'success'       => true,
            'user'          => $user->only(['username', 'email', 'role']),
            'permissions'   => [], // Replace with actual permissions if applicable
            'role'          => $user->role,
            'token'         => $token
        ], 200);
    }

    public function logout()
    {
        //remove "token" JWT
        JWTAuth::invalidate(JWTAuth::getToken());

        //response "success" logout
        return response()->json([
            'success' => true,
        ], 200);
    }
}


