<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;


Route::post('/users', [AuthorizationController::class, 'registerUser']);
Route::post('/login', [AuthorizationController::class, 'loginUser']);

Route::post('/users/{userId}/timeline', [PostController::class, 'createPost']);
