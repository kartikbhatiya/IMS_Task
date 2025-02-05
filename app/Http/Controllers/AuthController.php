<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ResponseTrait;
use App\Models\User;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ResponseTrait;
    public function register(Request $request)
    {
        try {
            $fields = $request->validate([
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[^a-zA-Z0-9]/',
            ], [
                'password.min' => 'The password must be at least 8 characters.',
                'password.regex' => 'The password must contain at least one lowercase letter. one uppercase letter, one number, and one special character.',
            ]);

            $user = User::create([
                'username' => $fields['username'],
                'password' => Hash::make($fields['password']),
            ]);

            return $this->Res(201, ['user' => $user], 'Registration Successfull');
        } catch (ValidationException $e) {
            return $this->ErrRes(422, $e->errors(), 'Validation Errors');
        } catch (Exception $e) {
            return $this->ErrRes(500, ['errors' => $e], 'Internal Server Error');
        }
    }

    public function login(Request $request)
    {
        try {
            $fields = $request->validate([
                'username' => 'required|string|exists:users,username',
                'password' => 'required|string'
            ], [
                'username.exists' => 'Username does not exist'
            ]);

            $user = User::where('username', $fields['username'])->first();

            if (!Hash::check($fields['password'], $user->password)) {
                return $this->ErrRes(404, ['password' => 'Password is Incorrect'], 'Logged In Failed');
            }

            $user = User::where('username', $fields['username'])->first();
            $token = $user->createToken('auth')->plainTextToken;

            // $cookie = cookie('token', $token, 60); // 60 minutes

            return $this->Res(200, ['user' => $user, 'token' => $token], 'Login Successful.');
        } catch (ValidationException $e) {
            return $this->ErrRes(422, $e->errors(), 'Validation Errors');
        }
    }

    public function logout(Request $request){
        try {
            $request->user()->tokens()->delete();
            return $this->Res(200, [], 'Logged Out Successfully');
        } catch (Exception $e) {
            return $this->ErrRes(500, ['errors' => $e], 'Internal Server Error');
        }
    }
}
