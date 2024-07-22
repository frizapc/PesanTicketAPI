<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginValidator;
use App\Http\Requests\RegistrationValidator;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController
{
    public function  register(RegistrationValidator $request){
        $validator = $request->validated();
        $user = User::create($validator);       
        $user = [
                    'name'=> $user['name'],
                    'email'=> $user['email'],
                ];
        return new AuthResource("Akun anda berhasil dibuat", 201, $user);
    }

    public function  login(LoginValidator $request, AuthenticationException $th){
        $validator = $request->validated();

        if(!Auth::attempt($validator)){
            throw $th;
        }

        $user = $request->user();
        $user->tokens()->where('tokenable_id', $user['id'])->delete();
        $token = $user->createToken('Auth Token', ['user'],now()->addDays(1));

        return new AuthResource("Berhasil login", 200, $token->plainTextToken);
    }

    public function  logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return new AuthResource("Anda telah logout", 200);
    }
}
