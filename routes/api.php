<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("/auth/register", [UserController::class, "register"]);
