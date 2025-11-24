<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function UserList() {
    $users = User::all(); // অথবা অন্য query
    return response()->json($users);
}
}
