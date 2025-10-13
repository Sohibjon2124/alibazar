<?php

namespace App\Http\Services;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Tymon\JWTAuth\Facades\JWTAuth;


class AuthService
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register($request)
    {
        $user = $this->userService->create($request);

        // Автоматически логиним
        $token = JWTAuth::fromUser($user);

        $refreshToken = $this->generateToken($user);

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'refresh_token' => $refreshToken
        ];
    }

    public function login($request)
    {
        $credentials = [
            'tel_number' => $request->tel,
            'password' => $request->password,
        ];

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // $user = auth()->user();
        $user = JWTAuth::user();
        $refreshToken = $this->generateToken($user);

        return [
            'user'=>[
                'id'=>$user->id,
                'name'=>$user->name,
                'tel_number'=>$user->tel_number
            ],
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'refresh_token' => $refreshToken
        ];
    }

    public function refresh($request)
    {
        $refreshToken = $request->input('refresh_token');

        $user = User::where('refresh_token', hash('sha256', $refreshToken))
            ->where('refresh_token_expires_at', '>', now())
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }

        $refreshToken = $this->generateToken($user);

        // Генерируем новый access token
        $token = JWTAuth::fromUser($user);

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'refresh_token' => $refreshToken
        ];
    }
    private function generateToken($user)
    {
        $refreshToken = Str::random(64);
        $user->refresh_token = hash('sha256', $refreshToken);
        $user->refresh_token_expires_at = now()->addDays(7);
        $user->save();

        return $refreshToken;
    }

    public function logout()
    {
        $user = auth()->user();
        $user->refresh_token = null;
        $user->refresh_token_expires_at = null;
        $user->save();

        auth()->logout();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
