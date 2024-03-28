<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admins\Auth\ConfirmPasswordController;
use App\Http\Controllers\Admins\Auth\ForgotPasswordController;
use App\Http\Controllers\Admins\Auth\LoginController;
use App\Http\Controllers\Admins\Auth\RegisterController;
use App\Http\Controllers\Admins\Auth\ResetPasswordController;
use App\Http\Controllers\Admins\Auth\VerificationController;

// Controller
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
// Custom auth routes
Auth::routes(['verify' => true]);

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::prefix("email")->controller(VerificationController::class)->group(function () {
    Route::get('/verify', 'show')->name('verification.notice');
    Route::get('/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::post('/resend', 'resend')->name('verification.resend');
});

Route::prefix("password")->group(function () {
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('/reset', 'showLinkRequestForm')->name('password.request');
        Route::post('/email', 'sendResetLinkEmail')->name('password.email');
    });

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('/reset/{token}', 'showResetForm')->name('password.reset');
        Route::post('/reset', 'reset')->name('password.update');
    });

    Route::controller(ConfirmPasswordController::class)->group(function () {
        Route::get('/confirm', 'showConfirmForm')->name('password.confirm');
        Route::post('/confirm', 'confirm');
    });
});


Route::prefix("email")->group(function () {
    //The Email Verification Notice
    Route::get('/verify', function () {
        return view('auth.verify');
    })->name('verification.notice');

    //The Email Verification Handler
    Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        // Thêm session
        session()->flash('welcome', true);

        return redirect('/');
    })->name('verification.verify');
});

// File manager
Route::group(['prefix' => 'laravel-filemanager'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

// =====================================ROUTE MAIN=============================================
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name("dashboard");

});
