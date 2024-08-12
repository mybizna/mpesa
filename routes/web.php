<?php

use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], 'kemomo/confirm', 'MpesaController@confirm')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['get', 'post'], 'kemomo/validate', 'MpesaController@validate')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['get', 'post'], 'callback', 'MpesaController@validate')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['get', 'post'], 'kemomo/reversal_queue', 'MpesaController@validate')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['get', 'post'], 'kemomo/reversal_result', 'MpesaController@validate')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['get', 'post'], 'kemomo/simulate', 'MpesaController@simulate');
Route::post('mpesa/paybill', 'MpesaController@paybill')->name('mpesa_paybill');
Route::post('mpesa/tillno', 'MpesaController@tillno')->name('mpesa_tillno');
Route::post('mpesa/stkpush', 'MpesaController@stkpush')->name('mpesa_stkpush');
