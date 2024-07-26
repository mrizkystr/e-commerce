<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UpdateTest extends Controller
{
    public function testing(Request $request, $id)
{
    // Mengonversi array menjadi string JSON sebelum log
    Log::info('Data yang diterima dari request: ' . json_encode($request->all()));

    return response()->json(['lihat log...'], 200);

    // $user = User::findOrFail($id);

    // $validate = Validator::make([
    //     'username' => $request->username,
    //     'email' => $request->email,
    //     'no_telp' => $request->no_telp,
    //     'password' => $request->password,
    // ]);

    // if($validate->fails()){
    //     return response()->json($validate->errors(), 422);
    // }

    // $user->update([
    //     'username' => $request->username,
    //     'email' => $request->email,
    //     'no_telp' => $request->no_telp,
    //     'password' => $request->password,
    // ]);
}

}
