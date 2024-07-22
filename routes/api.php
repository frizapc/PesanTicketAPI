<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('/register', 'register');

        Route::post('/login', 'login');

        Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::prefix('user')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('', 'dashboard')->middleware('auth:sanctum');
});

Route::prefix('events')
    ->controller(EventController::class)
    ->missing(function () {return new EventResource('Event tidak ditemukan', 404);})
    ->group(function () {
        Route::post('', 'create')->middleware('auth:sanctum');
        Route::get('', 'findAll');
        Route::get('/{event}', 'findOne');
        Route::put('/{event}', 'update')->middleware('auth:sanctum');
        Route::delete('/{event}', 'deleteOne')->middleware('auth:sanctum');
        Route::delete('', 'deleteAll')->middleware('auth:sanctum');

        Route::post('{event}/register', 'attendeeRegister')->middleware('auth:sanctum');
        Route::post('{event}/check-in', 'organizerCheckIn')->middleware('auth:sanctum');
});

Route::prefix('tickets')
    ->controller(TicketController::class)
    ->missing(function () {return new EventResource('Event tidak ditemukan', 404);})
    ->group(function () {
        Route::post('/event/{event}', 'purchase')->middleware('auth:sanctum');
        Route::get('/{ticket}', 'getDetail')->middleware('auth:sanctum');
});

Route::prefix('email')
    ->controller(EventController::class)
    ->group(function () {
        Route::post('', 'emailTest');
});

Route::fallback(function () {
    return new EventResource('Page Not Found', 404);
});