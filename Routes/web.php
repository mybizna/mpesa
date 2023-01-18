<?php

use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], 'safaricom/confirm', 'MpesaController@confirm');
Route::match(['get', 'post'], 'safaricom/validate', 'MpesaController@validate');
Route::match(['get', 'post'], 'safaricom/reversal_queue', 'MpesaController@validate');
Route::match(['get', 'post'], 'safaricom/reversal_result', 'MpesaController@validate');
Route::post('mpesa/paybill', 'MpesaController@paybill')->name('mpesa_paybill');
Route::post('mpesa/tillno', 'MpesaController@tillno')->name('mpesa_tillno');
Route::post('mpesa/stkpush', 'MpesaController@stkpush')->name('mpesa_stkpush');
