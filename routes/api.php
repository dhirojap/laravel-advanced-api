<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthApiMiddleware;
use Illuminate\Support\Facades\Route;

Route::post("/auth/register", [UserController::class, "register"]);
Route::post("/auth/login", [UserController::class, "login"]);

Route::middleware(AuthApiMiddleware::class)->group(function () {
    Route::get("/auth/users/current", [UserController::class,"getUser"]);
    Route::patch("/auth/users/current", [UserController::class,"updateUser"]);
    Route::delete("/auth/logout", [UserController::class,"logout"]);
});
