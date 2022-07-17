<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserSignupRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\Users\AccountVerified;
use App\Mail\Users\SendOtp;
use App\Mail\Users\Welcome;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserAuthController extends Controller
{
    public function signup(StoreUserSignupRequest $request)
    {
        $user = User::create($request->validated());

        $user->addMediaFromRequest('nib_photo')->toMediaCollection('nib_photo');

        return response()->json(['message' => 'Signup successful']);
    }

    public function login(Request $request)
    {
        return User::login($request);
    }

    public function logout()
    {
        return User::logout();
    }

    public function getProfile()
    {
        return response()->json(auth()->user());
    }

    public function updateProfile(UpdateUserRequest $request)
    {
        $user = User::find(auth()->id());

        $user->update($request->validated());

        if ($request->has('photo')) {
            $request->user()->clearMediaCollection('photo');
            $request->user()->addMediaFromRequest('photo')->toMediaCollection('photo');
        }

        $user->refresh();
        return response()->json($user);
    }

    public function sendForgotPasswordOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users'
        ]);

        $token = random_int(100000, 999999);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        $user = User::where('email', $request->email)->first();

        Mail::to($user)->send(new SendOtp($token));

        return response()->json(['message' => 'OTP sent']);
    }

    public function confirmForgotPasswordOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'token' => 'required|string|max:6'
        ]);

        $match = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();

        if ($match)
            return response()->json(true);
        else {
            return response()->json(false);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6'
        ]);

       $match = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();

       if($match){
           $user = User::where('email', $request->email)->first();

           $user->update([
               'password' => Hash::make($request->password)
           ]);

           return response()->json(['message' => 'Password Reset Successfull']);
       }

       abort(500);
    }
}
