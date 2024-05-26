<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');

    Route::post('/login', 'login');

    Route::get('/logout', 'logout')->middleware('auth:sanctum');
});

Route::prefix('user')->controller(UserController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->middleware('auth:sanctum');
});

// Route::post('/user/login', function (Request $request){
//     if(Auth::attempt($request->all())){
//         $user = User::where('email', $request->email)->first();
//         $token = $user->createToken('admin', ['admin']);
//         return ['token' => $token->plainTextToken];
//     }
//     return 'gagal';
// });

// Route::get('/user/{id}', function (string $id) {
//     return new UserResource(User::findOrFail($id));
// });

// Route::get('/dashboard', function (Request $request){
//     $user = $request->user();
//     if($user->tokenCan('admin')){
//         return "Dashboard Page";
//     }

//     return "Akses ditolak";
// })->middleware('auth:sanctum');