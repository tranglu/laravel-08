<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
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
    ->name('login');

$limiter = config('fortify.limiters.login');
$twoFactorLimiter = config('fortify.limiters.two-factor');

Route::post('/fortify-login', [AuthController::class,'login'])
    ->middleware(array_filter([
        'guest:'.config('fortify.guard'),
        $limiter ? 'throttle:'.$limiter : null,
    ]))->name('api-fortify-login');

Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware(['guest:'.config('fortify.guard')])
    ->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])
            ->middleware(['guest:'.config('fortify.guard')])
            ->name('api-fortify-register');
//Route::post('/register', [AuthController::class, 'register'])
//            ->middleware(['guest:'.config('fortify.guard')])
//            ->name('api-auth-register');
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UserController::class,'index']);

    Route::post('/logout', [UserController::class, 'logout'])
        ->name('api-fortify-logout');

    Route::get('/me', [UserController::class,'getCurrentUser'])->name('api-get-current-user');
    Route::post('/all-user', [UserController::class,'index'])->name('api-get-all-user');
    // Password Reset...
//    if (Features::enabled(Features::resetPasswords())) {
//        if ($enableViews) {
//            Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
//                ->middleware(['guest:'.config('fortify.guard')])
//                ->name('password.request');
//
//            Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
//                ->middleware(['guest:'.config('fortify.guard')])
//                ->name('password.reset');
//        }
//
//        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//            ->middleware(['guest:'.config('fortify.guard')])
//            ->name('password.email');
//
//        Route::post('/reset-password', [NewPasswordController::class, 'store'])
//            ->middleware(['guest:'.config('fortify.guard')])
//            ->name('password.update');
//    }
//
//    // Registration...
//    if (Features::enabled(Features::registration())) {
//        if ($enableViews) {
//            Route::get('/register', [RegisteredUserController::class, 'create'])
//                ->middleware(['guest:'.config('fortify.guard')])
//                ->name('register');
//        }
//
//        Route::post('/register', [RegisteredUserController::class, 'store'])
//            ->middleware(['guest:'.config('fortify.guard')]);
//    }
//
//    // Email Verification...
//    if (Features::enabled(Features::emailVerification())) {
//        if ($enableViews) {
//            Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
//                ->middleware(['auth'])
//                ->name('verification.notice');
//        }
//
//        Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
//            ->middleware(['auth', 'signed', 'throttle:6,1'])
//            ->name('verification.verify');
//
//        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//            ->middleware(['auth', 'throttle:6,1'])
//            ->name('verification.send');
//    }
//
//    // Profile Information...
//    if (Features::enabled(Features::updateProfileInformation())) {
//        Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
//            ->middleware(['auth'])
//            ->name('user-profile-information.update');
//    }
//
//    // Passwords...
//    if (Features::enabled(Features::updatePasswords())) {
//        Route::put('/user/password', [PasswordController::class, 'update'])
//            ->middleware(['auth'])
//            ->name('user-password.update');
//    }
//
//    // Password Confirmation...
//    if ($enableViews) {
//        Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])
//            ->middleware(['auth'])
//            ->name('password.confirm');
//    }
//
//    Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
//        ->middleware(['auth'])
//        ->name('password.confirmation');
//
//    Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])
//        ->middleware(['auth']);
//
//    // Two Factor Authentication...
//    if (Features::enabled(Features::twoFactorAuthentication())) {
//        if ($enableViews) {
//            Route::get('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
//                ->middleware(['guest:'.config('fortify.guard')])
//                ->name('two-factor.login');
//        }
//
//        Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
//            ->middleware(array_filter([
//                'guest:'.config('fortify.guard'),
//                $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
//            ]));
//
//        $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
//            ? ['auth', 'password.confirm']
//            : ['auth'];
//
//        Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
//            ->middleware($twoFactorMiddleware);
//
//        Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
//            ->middleware($twoFactorMiddleware);
//
//        Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
//            ->middleware($twoFactorMiddleware);
//
//        Route::get('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
//            ->middleware($twoFactorMiddleware);
//
//        Route::post('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
//            ->middleware($twoFactorMiddleware);
//    }


});

Route::post('/auth/logout', [UserController::class,'logout'])->name('api-auth-logout');
