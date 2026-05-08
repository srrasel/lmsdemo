<?php

use Illuminate\Support\Facades\Route;

Route::namespace('User')->name('user.')->middleware('guest')->group(function () {
    Route::controller('SocialiteController')->group(function () {
        Route::get('social-login/{provider}', 'socialLogin')->name('social.login');
        Route::get('social-login/callback/{provider}', 'callback')->name('social.login.callback');
    });
});

Route::name('user.')->middleware(['auth', 'check.status'])->group(function () {
    Route::controller(\App\Http\Controllers\Gateway\PaymentController::class)->group(function(){
        Route::get('deposit/confirm', 'depositConfirm')->name('deposit.confirm');
        Route::get('deposit/manual', 'manualDepositConfirm')->name('deposit.manual.confirm');
        Route::post('deposit/manual', 'manualDepositUpdate')->name('deposit.manual.update');
        Route::get('deposit/history', 'deposit')->name('deposit.history');
    });
});
