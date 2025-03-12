<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Stringable;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            Log::info('Login Attempt', ['email' => $request->email]);

            $user = User::firstWhere('email', $request->email);
            if ($user && Hash::check($request->password, $user->password)) {
                Log::info('Login Successful', ['user_id' => $user->id]);

                return response()->json([
                    'status' => 'user login',
                    'token' => $user->createToken('user')->plainTextToken,
                ]);
            } else {
                Log::warning('Login Failed', ['email' => $request->email]);

                return response()->json([
                    'status' => 'login failed',
                    'message' => 'Invalid credentials'
                ], 401);
            }
        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());

            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function register(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8'
    ]);

    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('user')->plainTextToken;

        return response()->json([
            'status' => 'user created',
            'user' => $user,
            'token' => $token
        ]);
    } catch (Exception $e) {
        return response()->json(['error' => 'Something went wrong'], 500);
    }
}

}
