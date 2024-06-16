<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserController
{
    public function  dashboard(Request $request){
        $user = $request->user();
        $user->userEvents;
        return new UserResource("User ditemukan", 200, $user);
    }
}
