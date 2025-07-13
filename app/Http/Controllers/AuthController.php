<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request) {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'employeeid' => 'required|unique:users|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Insert data of the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'employeeid' => $request->employeeid,
            'password' => bcrypt($request->password)
        ]);

        Auth::login($user);

        // Redirect to home
        return redirect()->route('main')->with('message', 'Registration successful! Welcome, ' . $user->name);
    }
}