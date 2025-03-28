<?php

use App\Http\Controllers\api\ApiController;
use App\Http\Controllers\api\v1\PaykuberController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/generate-token',[ApiController::class,'generateNewToken']);

Route::middleware(ApiAuthMiddleware::class)->controller(ApiController::class)->group(function(){
    Route::get('/user-data','fetchUserData');
    Route::post('/regenerate-token','regenerateUserToken');
});

Route::middleware(ApiAuthMiddleware::class)->controller(PaykuberController::class)->prefix('v1')->group(function(){
    Route::post('/pay-request','payRequest'); // Active
    Route::post('/seamless/txn-status','seamlessTxnStatus'); // Active
    Route::post('/payout-request','payoutRequest'); // Active
    Route::post('/utr-update','utrUpdate'); // Active
    Route::post('/balance','checkBalance');
    Route::post('/payin/trigger-callback','payinTriggerCallback');
});

Route::post('webhook/paykuber',[PaykuberController::class,'webHookHandler']); // Active