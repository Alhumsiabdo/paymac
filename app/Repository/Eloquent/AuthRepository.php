<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $userData)
    {
        $name = $userData['name'];
        $email = $userData['email'];
        $password = $userData['password'];

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->save();

        return true;
    }

    public function login(array $credentials)
    {
        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        return $this->respondWithToken($token, $user);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out', 'code' => 200]);
    }


    protected function respondWithToken($token, ?Authenticatable $user)
    {
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'code' => 200,
        ]);
    }
}