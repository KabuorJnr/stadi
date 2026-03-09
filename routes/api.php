<?php

use App\Http\Controllers\Api\MpesaController;
use App\Http\Controllers\Api\ScannerController;
use App\Http\Middleware\VerifyScannerApiKey;
use Illuminate\Support\Facades\Route;

// ─── QR Scanner Endpoints (API key protected) ──────────
Route::middleware(VerifyScannerApiKey::class)->group(function () {
    Route::post('/validate-ticket', [ScannerController::class, 'validate']);
    Route::get('/event/{event}/attendance', [ScannerController::class, 'attendance']);
});

// ─── M-PESA Endpoints ──────────────────────────────────
Route::prefix('mpesa')->group(function () {
    Route::post('/stk-push', [MpesaController::class, 'stkPush']);
    Route::post('/stk-callback', [MpesaController::class, 'stkCallback']);
    Route::post('/c2b/confirm', [MpesaController::class, 'c2bConfirm']);
    Route::post('/c2b/validate', [MpesaController::class, 'c2bValidate']);
});
