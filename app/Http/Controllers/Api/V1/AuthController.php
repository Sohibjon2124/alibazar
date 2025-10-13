<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\AuthService;
use App\Http\Services\UserService;
use Illuminate\Http\Request;
// use Tymon\JWTAuth\Facades\JWTAuth;
// use Tymon\JWTAuth\JWTAuth\JWTAuth;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    private $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(RegisterRequest $request)
    {
        return response()->json($this->authService->register($request), 201);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        return response()->json($this->authService->login($request));
    }

    public function refresh(Request $request)
    {
        return response()->json($this->authService->refresh($request));
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
    public function logout()
    {
        return $this->authService->logout();
    }
}
