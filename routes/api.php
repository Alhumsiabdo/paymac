<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['middleware' => 'api','prefix' => 'auth'], function () {

    // Auth
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::group(['middleware' => 'auth'], function () {
        // Blog CRUD operations
            Route::get('getUserBlogs', [BlogController::class, 'getUserBlogs']);
            Route::post('createBlog', [BlogController::class, 'createBlog']);
            Route::put('updateBlog', [BlogController::class, 'updateBlog']);
    });
});