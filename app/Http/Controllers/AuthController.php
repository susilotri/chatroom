<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $googleUser->getEmail())->first();
        
        if (!$user) {
            return response()->json([
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
            ], 200);
        }

        // Generate token
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'google_id' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'nickname' => 'required|string|unique:users,nickname',
        ]);

        $user = User::create([
            'google_id' => $request->google_id,
            'email' => $request->email,
            'name' => $request->name,
            'nickname' => $request->nickname,
            'role' => 'user', 
        ]);

        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ]);
    }
}

