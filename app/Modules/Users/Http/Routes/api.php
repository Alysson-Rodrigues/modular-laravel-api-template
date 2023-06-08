<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Users\Http\Controllers\UsersController;

// Group all these routes to protect them with auth middleware

Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/users', [UsersController::class, 'index']);
  Route::post('/users/store', [UsersController::class, 'store']);
  Route::delete('/users/{user}/destroy', [UsersController::class, 'destroy']);
  Route::get('/profile', [UsersController::class, 'me']);
  Route::post('/users/{user}/update', [UsersController::class, 'update']);
});

