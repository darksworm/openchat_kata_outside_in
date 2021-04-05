<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\FollowingsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/users', [AuthorizationController::class, 'registerUser']);
Route::post('/login', [AuthorizationController::class, 'loginUser']);

Route::get('/users', [UserController::class, 'getAllUsers']);

Route::post('/users/{userId}/timeline', [PostController::class, 'createPost']);
Route::get('/users/{userId}/timeline', [PostController::class, 'getTimeline']);

Route::post('/followings', [FollowingsController::class, 'createFollowing']);
Route::get('/followings/{followerId}/followees', [FollowingsController::class, 'getFollowees']);
