<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SongController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/auth/register', [AuthController::class,'register'])->name('api-auth-register');
Route::post('/auth/login', [AuthController::class,'login'])->name('api-auth-login');
Route::get('/auth/check', [AuthController::class,'check'])->name('api-auth-check');
Route::get('/deny', [AuthController::class,'deny'])->name('api-auth-deny');

//Fortify
// Authentication...

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('login');// trả về giao diện login

$limiter = config('fortify.limiters.login');
$twoFactorLimiter = config('fortify.limiters.two-factor');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware(array_filter([
        'guest:'.config('fortify.guard'),
        $limiter ? 'throttle:'.$limiter : null,
    ]))->name('api-login');// login vào app

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware(['guest:'.config('fortify.guard')])
            ->name('api-fortify-register');// dùng cho đăng ký của fortify

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [UserController::class, 'logout'])
        ->name('api-auth-logout');

    Route::get('/me', [UserController::class,'getCurrentUser'])->name('api-get-current-user');

    Route::resource('songs', SongController::class);
    Route::get('/users', [UserController::class,'index'])->name('api-get-all-user');
//    Route::get('/songs', [SongController1::class,'index'])->name('api-get-all-song');
//    Route::POST('/songs', [SongController1::class,'store'])->name('api-store-song');

});

