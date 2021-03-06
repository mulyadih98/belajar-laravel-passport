<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('auth')->group(function(){
    Route::post('login',[AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::group(['middleware' => 'auth:api'], function(){
        Route::get('user', [AuthController::class, 'profile']);
        Route::get('logout', [AuthController::class, 'logout']);
    });
    
});

Route::middleware('auth:api')->get('/user/get', [UserController::class, 'get']);

