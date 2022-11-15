<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    // Show register/create form
    public function create(){
        return view('users.register');
    }

    //create new user
    public function store(Request $request){
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6'
        ]);

        //Hash password
        $formFields['password'] =bcrypt($formFields['password']);

        //create user
        $user = User::create($formFields);

        //event verify email
        event(new Registered($user));

        //login
        auth()->login($user);

        return redirect('/')->with('message', 'User created and logged in');
    }

    //log out
    public function logout(Request $request){
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect('/')->with('message', 'You have been logged out!');
    }

    //show login form
    public function login(){
        return view('users.login');
    }

    //authenticate
    public function authenticate(Request $request){
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        if(auth()->attempt($formFields)){
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You are now logged in!');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }
}