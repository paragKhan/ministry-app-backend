<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApproverAuthController;
use App\Http\Controllers\ApproverController;
use App\Http\Controllers\ExecutiveAuthController;
use App\Http\Controllers\ExecutiveController;
use App\Http\Controllers\HousingModelController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SubdivisionController;
use App\Http\Controllers\SupportConversationController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use App\Models\Manager;
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
        //Upload photo
        Route::get('logout', [UserAuthController::class, 'logout']);
        Route::get('profile', [UserAuthController::class, 'getProfile']);
        Route::put('profile', [UserAuthController::class, 'updateProfile']);

        //support
        Route::get('support_conversations/{conversation}/resolve', [SupportConversationController::class, 'resolveConversation']);
        Route::get('support_conversations/history', [SupportConversationController::class, 'myHistory']);
        Route::post('support_conversations/{conversation}/send-message', [SupportConversationController::class, 'sendMessage']);
        Route::apiResource('support_conversations', SupportConversationController::class)->except('index', 'update', 'destroy');
    });

});


Route::prefix('admin')->group(function () {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::middleware('auth:api_admin')->group(function () {
        Route::get('logout', [AdminAuthController::class, 'logout']);
        Route::apiResource('users', UserController::class);

        //support
        Route::get('support_conversations/{conversation}/resolve', [SupportConversationController::class, 'resolveConversation']);
        Route::post('support_conversations/{conversation}/send-message', [SupportConversationController::class, 'sendMessage']);
        Route::apiResource('support_conversations', SupportConversationController::class)->except('update');
    });

});

