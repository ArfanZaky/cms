<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function profile(Request $request)
    {
        return $request->user();
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device' => ['required', 'string'],
        ]);

        $request->merge(['status' => true]);

        if (Auth::guard()->attempt($request->only('email', 'password', 'status'))) {
            $user = Auth::user();

            return response()->json([
                'status' => [
                    'code' => 200,
                    'message' => 'Ok',
                ],
                'data' => [
                    'access_token' => $user->createToken($request->device)->plainTextToken,
                    'token_type' => 'Bearer',
                ],
            ], 200);
        }

        return response()->json([
            'status' => [
                'code' => 401,
                'message' => 'Unauthorized',
            ],
            'data' => [],
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => [
                'code' => 200,
                'message' => 'Ok',
            ],
            'data' => [],
        ], 200);
    }
}
