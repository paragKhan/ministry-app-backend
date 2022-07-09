<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SupportConversationController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
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

//User
Route::prefix('user')->group(function () {
    Route::post('login', [UserAuthController::class, 'login']);
    Route::post('signup', [UserAuthController::class, 'signup']);

    //Email Verification
//    Route::post('send-verification-email', [UserAuthController::class, 'sendVerificationEmail'])->middleware('auth:api_user');
//    Route::get('verify-email/{id}/{hash}', [UserAuthController::class, 'verifyEmail'])->name('verification.verify')->middleware('auth:api_user');

    //Forgot and Reset password
//    Route::post('forgot-password', [UserAuthController::class, "forgotPassword"]);
//    Route::post('reset-password', [UserAuthController::class, "resetPassword"])->name('password.reset');

    Route::middleware('auth:api_user')->group(function () {
        Route::get('logout', [UserAuthController::class, 'logout']);
        Route::get('profile', [UserAuthController::class, 'getProfile']);
        Route::put('profile', [UserAuthController::class, 'updateProfile']);

        //Blog
        Route::get('blogs/{blog}/toggle-react', [BlogController::class, 'toggleReact']);
        Route::apiResource('blogs', BlogController::class)->only('index', 'show');

        //Comment
        Route::post('comments', [CommentController::class, 'postComment']);
        Route::delete('comments/{comment}', [CommentController::class, 'deleteComment']);
    });

});


Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::middleware('auth:api_admin')->group(function () {
        Route::get('logout', [AdminAuthController::class, 'logout']);
        Route::apiResource('users', UserController::class);

        //Blog
        Route::apiResource('blogs', BlogController::class);
    });

});

