<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Pail\ValueObjects\Origin\Console;
use Laravel\Socialite\Two\InvalidStateException;

class AuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $credential = $request->input('credential');

            if (!$credential) {
                return response()->json(['error' => 'Credential is missing'], 400);
            }

            // Verifikasi token ke Google API
            $googleResponse = Http::get('https://www.googleapis.com/oauth2/v3/tokeninfo', [
                'id_token' => $credential,
            ]);

            if ($googleResponse->failed()) {
                return response()->json(['error' => 'Invalid Google token'], 401);
            }

            $googleUser = $googleResponse->json();

            $user = User::where('email', $googleUser['email'])->first();

            if (!$user) {
                return response()->json([
                    'google_id' => $googleUser['sub'],
                    'email' => $googleUser['email'],
                    'name' => $googleUser['name'],
                ], 200);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'user' => $user,
            ]);
        } catch (InvalidStateException $e) {
            return response()->json(['error' => 'Invalid state'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error occurred'], 500);
        }
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

    public function logoutUser(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout successful']);
    }

    public function getUsers(Request $request)
    {
        $users = User::all();
        return response()->json($users);
    }
}
