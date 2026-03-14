<?php

namespace App\Http\Repositories;

use App\Http\Requests\Api\V1\RegisterRequest;

interface UserRepositoryInterface
{
    public function create(RegisterRequest $request);
    public function findByValidRefreshToken(string $refreshToken);
}
