<?php

use App\Http\Controllers\Ussd\UssdController;
use Illuminate\Support\Facades\Route;

Route::post('/ussd', [UssdController::class, 'handle'])->name('ussd.handle');
