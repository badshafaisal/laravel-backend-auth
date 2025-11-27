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
        return User::orderBy('id', 'desc')->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $fullname = $validated['first_name'] . ' ' . $validated['last_name'];

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'name' => $fullname,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'sometimes|string|max:255',
        'first_name' => 'sometimes|string|max:255',
        'last_name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $id,
        'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // Basic fields update
    $user->name = $request->name;
    $user->first_name = $request->first_name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;

    // IMAGE Upload
    if ($request->hasFile('image')) {

        // পুরনো ছবি delete
        if ($user->profile_image && file_exists(public_path('uploads/' . $user->profile_image))) {
            unlink(public_path('uploads/' . $user->profile_image));
        }

        // নতুন image save
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);

        // DB তে image path save
        $user->profile_image = $filename;
    }

    $user->save();

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user,
        'image_url' => $user->profile_image ? asset('uploads/' . $user->profile_image) : null
    ], 200);
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(string $id)
{
    $user = User::findOrFail($id); // User খুঁজে বের করা
    $user->delete(); // Delete করা

    return response()->json([
        'message' => 'User deleted successfully'
    ], 200);
}
}
