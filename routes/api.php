<?php

use App\Http\Controllers\AuthControlller;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',[AuthControlller::class,'create_user']);

Route::post('/login',[AuthControlller::class,'login_user']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-user',function(){
        return response()->json(auth()->user());
    });
    Route::post('/logout',[AuthControlller::class,'logout_user']);
});
