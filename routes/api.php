<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Resources\EventResource;
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
    ->middleware('auth:sanctum')
    ->missing(function () {return new EventResource('Event tidak ditemukan', 404);})
    ->group(function () {
        Route::get('', 'findAll')->withoutMiddleware('auth:sanctum');
        Route::get('/{event}', 'findOne')->withoutMiddleware('auth:sanctum');
        Route::post('', 'create');
        Route::put('/{event}', 'update');
        Route::delete('/{event}', 'deleteOne');
        Route::delete('', 'deleteAll');

        Route::post('{event}/register', 'attendeeRegister');
        Route::post('{event}/check-in', 'organizerCheckIn');
});

Route::prefix('tickets')
    ->controller(TicketController::class)
    ->middleware('auth:sanctum')
    ->missing(function () {return new EventResource('Event tidak ditemukan', 404);})
    ->group(function () {
        Route::post('/event/{event}', 'purchase');
        Route::get('/{ticket}', 'getDetail');
});

Route::prefix('email')
    ->controller(EventController::class)
    ->group(function () {
        Route::post('', 'emailTest');
});

Route::fallback(function () {
    return new EventResource('Page Not Found', 404);
});