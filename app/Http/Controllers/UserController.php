<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;



class UserController extends Controller
{
    //show signup page
    public function signup()
    {
        return view('users.register');
    }

    //create user
    public function store(Request $request)
    {
        //dd($request);
        $formFields = $request->validate([
            'name' => 'required',
            'email' => ['required','email',Rule::unique('users', 'email')],
            'password' => ['required','confirmed','min:6'],
        ]);
        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        //hash password
        $formFields['password']=bcrypt($formFields['password']);
        //create user
        $user=User::create($formFields);
        //login
        Auth::login($user, $remember = true);
        return redirect('/')->with('message', 'user registered and logged in');
    }

    //login page
    public function login()
    {
        return view('users.login');
    }

    //authenticate user
    public function authenticate (Request $request)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($formFields)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
