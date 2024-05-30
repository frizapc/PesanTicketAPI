<?php

namespace App\Http\Controllers;

use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserController
{
    public function  dashboard(Request $request, AuthenticationException $th){
        $user_token = $request->bearerToken();

        if (strpos($user_token, '|') === false) {
            throw $th;
        }

        $token_id= explode('|', $user_token)[0];
        $token = PersonalAccessToken::find($token_id);
        $user = User::find($token['tokenable_id']);
        return $user;
    }
}
