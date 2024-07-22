<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController
{
    public function  dashboard(Request $request){
        $user = $request->user();
        $user->userEvents;
        $user->userTickets;
        return new UserResource("User ditemukan", 200, $user);
    }
}
