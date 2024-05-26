<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginValidator;
use App\Http\Requests\RegistrationValidator;
use App\Http\Resources\LoginResource;
use App\Http\Resources\LogoutResource;
use App\Http\Resources\RegistrationResource;
use App\Models\User;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    public function  register(RegistrationValidator $request){
        $validator = $request->validated();
        $user = User::create($validator);
        return new RegistrationResource([$user]);
    }

    public function  login(LoginValidator $request){
        $validator = $request->validated();

        if(!Auth::attempt($validator)){
            return response()->json([
                'message' => 'Password salah'
            ], 422);
        }

        $user = $request->user();
        $user->tokens()->where('tokenable_id', $user['id'])->delete();
        $token = $user->createToken('API Token', ['*'],now()->addDays(1));

        return new LoginResource([$token]);
    }

    public function  logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return new LogoutResource([]);
    }
}
