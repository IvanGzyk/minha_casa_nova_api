<?php

use App\Http\Controllers\Api\Auth\AuthUserController;
use App\Http\Controllers\Api\UserController;
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
Route::post('/auth', [AuthUserController::class, 'auth'])->name('api.auth');


Route::group(['as' => 'api.'], function(){

    Route::middleware('auth:sanctum')->group(function(){

        Route::group(['prefix' => 'auth'], function(){
            Route::get('/me', [AuthUserController::class, 'me'])->name('me');
            Route::delete('/logout', [AuthUserController::class, 'logout'])->name('logout');
        });

        Route::group(['prefix' => 'v1'], function(){

            Route::apiResource('/users', UserController::class);

        });
    });

});
