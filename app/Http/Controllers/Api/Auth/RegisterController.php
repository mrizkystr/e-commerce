<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_telp' => 'required|string|max:15',
            'password' => 'required|string|min:8',
        ]);

        // Response error validasi
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buat user baru
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'password' => Hash::make($request->password),
            'role' => 'user', // Set role as 'user'
        ]);

       { // Response user created dengan data pengguna
        return response()->json([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'no_telp' => $user->no_telp,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ], 201);
    }
}
}