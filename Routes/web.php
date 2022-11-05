<?php

use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], 'mpesa/confirm', 'MpesaController@confirm');
Route::match(['get', 'post'], 'mpesa/validate', 'MpesaController@validate');
