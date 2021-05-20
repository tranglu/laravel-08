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


//$limiter = config('fortify.limiters.login');
//
//
//Route::post('/login', [AuthenticatedSessionController::class, 'store'])
//    ->middleware(array_filter([
//        'guest:'.config('fortify.guard'),
//        $limiter ? 'throttle:'.$limiter : null,
//    ]));
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/home', function () {
        return view('welcome');
    });
    Route::post('/logout', [UserController::class, 'logout'])
        ->name('api-auth-logout');
    Route::get('/users', [UserController::class,'index'])->name('api-get-all-user');
    Route::get('/me', [UserController::class,'getCurrentUser'])->name('api-get-current-user');
    Route::resource('songs', SongController::class);



});

