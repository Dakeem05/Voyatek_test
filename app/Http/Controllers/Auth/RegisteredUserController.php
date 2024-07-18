<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    use ApiResponseTrait;
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterUserRequest $request)
    {
        $_data = $request->validated();

        $user = User::create([
            'name' => $_data['name'],
            'email' => $_data['email'],
            'password' => Hash::make($_data['password']),
        ]);
        
        $token = $user->createToken('BlogApp')->accessToken;

        return $this->successResponse([
            "token" => $token
        ], "Signup successful", 201);
    }   
}
