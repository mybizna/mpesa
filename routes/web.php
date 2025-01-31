<?php

use Illuminate\Support\Facades\Route;
use Modules\Mpesa\Http\Controllers\MpesaController;


Route::match(['get', 'post'], 'kemomo/confirm',[MpesaController::class, 'confirm'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['get', 'post'], 'kemomo/validate', [MpesaController::class, 'validate'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['get', 'post'], 'callback', [MpesaController::class, 'validate'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['get', 'post'], 'kemomo/reversal_queue', [MpesaController::class, 'validate'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['get', 'post'], 'kemomo/reversal_result', [MpesaController::class, 'validate'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
Route::match(['get', 'post'], 'kemomo/simulate', [MpesaController::class, 'simulate']);
Route::post('mpesa/paybill', [MpesaController::class, 'paybill'])->name('mpesa_paybill');
Route::post('mpesa/tillno', [MpesaController::class, 'tillno'])->name('mpesa_tillno');
Route::post('mpesa/stkpush', [MpesaController::class, 'stkpush'])->name('mpesa_stkpush');
