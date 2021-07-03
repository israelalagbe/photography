<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 400);
        }

        $credentials = $validator->validated();

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized Access', 'status' => 'failed'], 401);
        }

        return response()->json([
            'data' => [
                'token' => $token,
                'user' => Auth::user()
            ]
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
            'role' => ['required', 'in:client,photographer']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 400);
        }

        $payload = $validator->validated();

        $payload['password'] = Hash::make($payload['password']);

        $user = User::create($payload);

        $token = Auth::login($user);

        return response()->json([
            'data' => [
                'token' => $token,
                'user' => Auth::user()
            ]
        ]);
    }

    public function getProfile()
    {
        return response()->json([
            'data' => Auth::user()
        ]);
    }
}
