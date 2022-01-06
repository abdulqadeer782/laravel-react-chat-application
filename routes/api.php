<?php

use App\Http\Controllers\AuthControlller;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\TestController;
use App\Models\Message;
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
    Route::get('/get-user',[AuthControlller::class,'getUser']);

    Route::get('/get-users',[ConversationController::class,'getAllUsers']);

    Route::get('/test',function(){
        $as = Message::where('id',2)->with('user')->first();
        return response($as);
    });

    Route::group(['prefix'=>'conversation'],function(){

        Route::post('/sent',[ConversationController::class,'sendMessage']);

        Route::post('/room',[ConversationController::class,'chatRoom']);

        Route::post('/room/{id}',[ConversationController::class,'deleteMessage']);

        Route::post('/delete',[ConversationController::class,'deleteConversation']);

    });

    Route::post('/logout',[AuthControlller::class,'logout_user']);

    Route::get('/check',[TestController::class,'index']);

});
