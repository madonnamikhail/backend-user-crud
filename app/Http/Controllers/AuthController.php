<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\User\LoginResource;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $data = [
            'email' => $request->email,
            'password' => $request->password,
            'token' => $token
        ];

        // return new LoginResource($data);
        return response()->json($data);
    }
}
