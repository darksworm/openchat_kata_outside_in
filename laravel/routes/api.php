<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;


Route::post('/users', [RegistrationController::class, 'registerUser']);
Route::post('/login', [LoginController::class, 'loginUser']);
Route::post('/users/{userId}/timeline', [PostController::class, 'createPost']);
