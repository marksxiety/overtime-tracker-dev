<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request) {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'employeeid' => 'required|unique:users|string|max:255',
            'role' => ['required', Rule::in(['employee', 'approver', 'admin'])],
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Insert data of the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'employeeid' => $request->employeeid,
            'password' => bcrypt($request->password)
        ]);

        Auth::login($user);

        // Redirect to home
        return redirect()->route('main')->with('message', 'Registration successful! Welcome, ' . $user->name);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->route('main')->with('message', 'Login Successful! Welcome back, ' . Auth::user()->name);
        }

        return redirect()->back()->withErrors(['email' => 'Email or Password is incorrect.', 'password' => 'Email or Password is incorrect.']);
    }
}