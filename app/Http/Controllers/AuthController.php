<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'Email or password incorrect!'], 401);
        }

        return response()->json([
            'data' => [
                'token' => $token,
                'user' => Auth::user()
            ]
        ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {

        $payload = $request->validated();

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
