<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         return User::paginate(2);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated =$request->validate([
            'first_name'=>'required|string|max:255',
            'last_name'=>'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password'=>'required|string|min:6|confirmed',
        ]);

        $fullname = $validated['first_name'].''.$validated['last_name'];

        $user =User::create([
            'first_name'=>$validated['first_name'],
            'last_name'=>$validated['last_name'],
            'name'=>$fullname,
            'email'=>$validated['email'],
            'password'=>Hash::make($validated['password']),
            'role'=>'user',
        ]);

        return response()->json([
            'message'=>'User created successfully',
            'user'=>$user
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
