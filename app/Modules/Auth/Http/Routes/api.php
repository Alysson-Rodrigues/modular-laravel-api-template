<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Http\Controllers\AuthenticateController;

Route::post('login', [AuthenticateController::class, 'login']);
Route::post('logout', [AuthenticateController::class, 'logout']);