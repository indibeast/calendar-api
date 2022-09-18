<?php

use App\Http\Controllers\Api\Auth\AccountRecoveryController;
use App\Http\Controllers\Api\Auth\LogInController;
use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\Events\EventController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', RegistrationController::class);
Route::post('login', LogInController::class);
Route::post('account-recovery', AccountRecoveryController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/events', EventController::class);
});
