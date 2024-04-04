<?php

use App\Http\Controllers\Users\AuthController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth
Route::prefix("auth")->controller(AuthController::class)->group(function () {
    Route::post('/register', "register")->name("user.auth.register");
    Route::post('/login', "login")->name("user.auth.login");

    Route::post('/forget-password', "forgetPassword")->name("user.auth.forgetPassword");
    Route::post('/reset-password/{token}', "resetPassword")->name("user.auth.resetPassword");

    Route::post('/check-token', 'checkToken')->name("user.auth.checkToken");

    Route::post('/verification', 'verificationSend')->name("user.auth.verification");
    Route::get('/verify-account/{idUser}/{token}', 'verificationGet')->name("user.auth.verificationGet");

    Route::post('/logout', "logout")->name("user.auth.logout");
});
