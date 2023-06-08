<?php

use App\Modules\Comments\Http\Controllers\CommentsController;
use Illuminate\Support\Facades\Route;

// Group routes in sanctum middleware 
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/comments', [CommentsController::class, 'index']);
    Route::post('/comments', [CommentsController::class, 'store']);
    Route::post('/comments/{comment}', [CommentsController::class, 'update']);
});
