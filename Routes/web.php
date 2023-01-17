<?php

use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], 'mpesa/confirm', 'MpesaController@confirm');
Route::match(['get', 'post'], 'mpesa/validate', 'MpesaController@validate');
Route::match(['get', 'post'], 'mpesa/reversal_queue', 'MpesaController@validate');
Route::match(['get', 'post'], 'mpesa/reversal_result', 'MpesaController@validate');
Route::post('mpesa/paybill', 'MpesaController@paybill')->name('mpesa_paybill');
Route::post('mpesa/tillno', 'MpesaController@tillno')->name('mpesa_tillno');
Route::post('mpesa/stkpush', 'MpesaController@stkpush')->name('mpesa_stkpush');
