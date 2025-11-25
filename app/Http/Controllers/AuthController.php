<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ১. রেজিস্টার ফাংশন
    public function register(Request $request)
{
    // ডাটা ভ্যালিডেশন
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    // full name create করা
    $fullName = $validated['first_name'] . ' ' . $validated['last_name'];

    // ইউজার তৈরি করা
    $user = User::create([
        'first_name' => $validated['first_name'],
        'last_name'  => $validated['last_name'],
        'name'       => $fullName,                      // 👈 IMPORTANT FIX
        'email'      => $validated['email'],
        'password'   => Hash::make($validated['password']),
        'role'       =>'user',
    ]);

    // অটোমেটিক লগইন
    Auth::login($user);

    return response()->json([
        'message' => 'User registered successfully',
        'user' => $user
    ], 201);
}


    // ২. লগইন ফাংশন (самое গুরুত্বপূর্ণ অংশ)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            // সেশন ফিক্সেশন অ্যাটাক আটকানোর জন্য সেশন রি-জেনারেট করা হয়
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login successful',
                'user' => Auth::user()
            ]);
        }

        // পাসওয়ার্ড বা ইমেইল ভুল হলে
        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    // ৩. লগআউট ফাংশন
    public function logout(Request $request)
    {
        // ওয়েব গার্ড থেকে লগআউট
        Auth::guard('web')->logout();

        // সেশন ধ্বংস করা
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function getUser(){
        $user = User::all();
        return $user;
    }
}