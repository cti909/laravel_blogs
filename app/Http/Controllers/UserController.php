<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class UserController extends Controller
{
    /**
     * redirect homepage
     */
    public function home()
    {
        return view('homepage.home');
    }
    /**
     * redirect login form
     */
    public function loginForm()
    {
        return view('accounts.login');
    }
    /**
     * check data form login form
     */
    public function loginCheck(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        // dd($credentials);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('')->with('success','Login is successful.');
        } else {
            return redirect()->back()
            ->withErrors('Username or password is wrong!')
            ->withInput();
        }
    }
    /**
     * logout
     */
    public function logout()
    {
        Session::flush(); // delete all session
        Auth::logout();
        return Redirect('login');
    }
    /**
     * redirect register form
     */
    public function registerForm()
    {
        return view('accounts.register');
    }
    /**
     * check and add new user
     */
    public function accountCreate(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users',
            'address' => 'required',
        ]);
        $user = User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'email' => $request->input('email'),
            'address' => $request->input('address')
        ]);
        auth()->login($user);
        return redirect('')->with('success','User has been created successfully.');
    }
}
