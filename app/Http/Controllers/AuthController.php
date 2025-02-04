<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $fields = $request->validate([
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string',
            ]);

            $user = User::create([
                'username' => $fields['username'],
                'password' => Hash::make($fields['password']),
            ]);

            if ($user) {
                return new JsonResponse(['user' => $user], 201);
            } else {
                return new JsonResponse(['message' => 'Registration failed'], 500);
            }
        } catch (ValidationException $e) {

            return new JsonResponse(['errors' => $e->errors()], 422);
        }
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:8'
        ]);

        if (Auth::attempt($fields)) {
            $user = DB::table('users');
            $token = $user->createToken('auth')->plainTextToken;

            return new JsonResponse(['user' => $user, 'token' => $token, 'message' => 'Login Successfull.'], 200);
        } else {
            return new JsonResponse(['message' => 'Login failed'], 401);
        }
    }
}
