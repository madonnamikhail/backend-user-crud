<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\User\LoginResource;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // return bcrypt('123456');
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $refreshToken = auth('api')->setTTL(1440)->claims(['is_refresh_token' => true])->fromUser(auth('api')->user());


        $data = [
            'email' => $request->email,
            'access_token' => $token,
            'refresh_token' => $refreshToken,
        ];

        // return new LoginResource($data);
        return response()->json($data);
    }

    public function refreshToken(Request $request)
    {
        try {
            $refreshToken = $request->bearerToken(); // Extract the refresh token from the Authorization header
            $newToken = auth('api')->setToken($refreshToken)->refresh();

            return response()->json([
                'access_token' => $newToken,
                'message' => 'Token refreshed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }
    }
}
