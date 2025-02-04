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
                'password' => 'required|string|min:8',
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
                'username' => 'required|string',
                'password' => 'required|string|min:8'
            ]);

            $user = User::where('username', $fields['username'])->first();

            if (!$user) {
                return $this->ErrRes(404, ['username' => 'Username does not exist'], 'Logged In Failed');
            }

            // Check if the password is correct
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
}
