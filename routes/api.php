<?php

use App\Http\Controllers\External\CryptoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\External\GiftCardController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\UserController;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class,'register']);
        Route::post('login',    [AuthController::class,'login']);
        Route::post('unlock',   [AuthController::class,'unlock']);
        Route::post('biometric/register', [AuthController::class,'registerBiometric']);
        Route::post('biometric/login',    [AuthController::class,'loginBiometric']);

        Route::post('otp/send',    [OtpController::class,'send']);
        Route::post('otp/send/guest',    [OtpController::class,'sendToGuest']);
        Route::post('otp/verify',  [OtpController::class,'verify']);
        Route::post('otp/verify/guest',  [OtpController::class,'verifyGuest']);
        Route::post('password/reset', [OtpController::class,'resetPassword']);

        Route::post('set-pin', [AuthController::class,'setTransactionPin']);
    });

    // uniqueness checks
    Route::post('unique/email',    [AuthController::class,'uniqueEmail']);
    Route::post('unique/username', [AuthController::class,'uniqueUsername']);
    Route::post('unique/phone',    [AuthController::class,'uniquePhone']);

    // protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('users', UserController::class)->only(['index','show','update','destroy']);
        // routes/api.php
    Route::get('/giftcards', [GiftCardController::class, 'getAllGiftcards']);
    Route::get('/cryptodata', [CryptoController::class, 'getAllCryptoData']);

    });
});
