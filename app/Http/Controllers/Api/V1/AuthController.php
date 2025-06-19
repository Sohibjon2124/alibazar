<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Автоматически логиним
        $token = JWTAuth::fromUser($user);

        // Генерируем refresh token
        $refreshToken = Str::random(64);
        $user->refresh_token = hash('sha256', $refreshToken);
        $user->refresh_token_expires_at = now()->addDays(7);
        $user->save();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'refresh_token' => $refreshToken
        ]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();

        // Генерация refresh token
        $refreshToken = Str::random(64);
        $user->refresh_token = hash('sha256', $refreshToken);
        $user->refresh_token_expires_at = now()->addDays(7);
        $user->save();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'refresh_token' => $refreshToken
        ]);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->input('refresh_token');

        $user = User::where('refresh_token', hash('sha256', $refreshToken))
            ->where('refresh_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }

        // Генерируем новый refresh token
        $newRefreshToken = Str::random(64);
        $user->refresh_token = hash('sha256', $newRefreshToken);
        $user->refresh_token_expires_at = now()->addDays(7);
        $user->save();

        // Генерируем новый access token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'refresh_token' => $newRefreshToken
        ]);
    }



    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->refresh_token = null;
        $user->refresh_token_expires_at = null;
        $user->save();

        auth()->logout();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
