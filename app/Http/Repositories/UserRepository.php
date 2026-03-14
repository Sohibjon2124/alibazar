<?php

namespace App\Http\Repositories;

use App\Http\Requests\Api\V1\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{

    protected $model;
    public function __construct(User $user)
    {
        $this->model = $user;
    }
    public function getAll() {}
    public function findById() {}
    public function create(RegisterRequest $request)
    {
        return $this->model->create([
            'name' => $request->name,
            'tel_number' => $request->tel,
            'password' => Hash::make($request->password),
        ]);
    }
    public function update() {}
    public function delete() {}

    public function findByValidRefreshToken(string $refreshToken)
    {
        return $this->model->where('refresh_token', hash('sha256', $refreshToken))
            ->where('refresh_token_expires_at', '>', now())
            ->first();
    }
}
