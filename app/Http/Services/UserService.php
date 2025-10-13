<?php

namespace App\Http\Services;

use App\Http\Resources\Api\V1\ProductResource;
use App\Http\Resources\Api\V1\PromotionResource;
use App\Models\ProductPromotion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Tymon\JWTAuth\Facades\JWTAuth;


class UserService
{
    public function create($request)
    {

        return User::create([
            'name' => $request->name,
            'tel_number' => $request->tel,
            'password' => Hash::make($request->password),
        ]);
    }
}
