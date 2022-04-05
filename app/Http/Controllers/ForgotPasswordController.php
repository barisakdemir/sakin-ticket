<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Str;
use App\Models\User;
use App\Models\PasswordReset;
use App\Jobs\SendForgotPasswordMail;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('forgot-password/index');
    }

    public function store(Request $request)
    {
        //validate
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        //generate hash
        //$token = Hash::make(Str::random(64));
        $token = Str::random(64);

        //store db
        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
        ]);

        //job for email
        SendForgotPasswordMail::dispatch($request->email);

        //redirect
        return back()->withSuccess('We have mailed your password reset link');
    }

    public function reset($token)
    {
        return view('forgot-password/reset', compact(['token']));
    }

    public function resetStore($token, Request $request)
    {
        //validate
        $request->validate([
            'password' => 'required|min:8|same:password_confirmation',
            'password_confirmation' => 'required|min:8',
        ]);

        //check token
        $checkToken = PasswordReset::where('token', $token)->first();
        if (!$checkToken) {
            return back()->withInput()->withErrors('Invalid token');
        }

        //update password
        User::where('email', $checkToken->email)->update(['password' => Hash::make($request['password'])]);

        //delete token
        PasswordReset::where('token', $token)->delete();

        //return
        return Redirect()->route('login')->withSuccess('Your password has been changed');
    }
}
