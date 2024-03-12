<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Repository\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterUserRequest $registerUserRequest)
    {
        $userData = $registerUserRequest->validated();

        if ($this->authRepository->register($userData)) {
            return response()->json(['message' => 'User registered successfully', 'code' => 200], 201);
        } else {
            return response()->json(['message' => 'Failed to register user'], 500);
        }
    }

    public function login(LoginRequest $loginRequest)
    {
        $credentials = $loginRequest->validated();
        // dd($credentials);

        return $this->authRepository->login($credentials);
    }


    public function logout()
    {
        return $this->authRepository->logout();
    }
}
