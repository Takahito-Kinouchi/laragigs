<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailController extends Controller
{
    public function send(){
        return view('auth.verify-email');
    }

    public function receive(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return redirect('/');
    }

    public function resend (Request $request){
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }





}