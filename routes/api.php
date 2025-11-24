<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// পাবলিক রাউট (লগইন ছাড়াই এক্সেস করা যাবে)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// প্রোটেক্টেড রাউট (লগইন করা থাকলেই কেবল কাজ করবে)
// middleware('auth:sanctum') চেক করে কুকি ঠিক আছে কিনা
Route::middleware(['auth:sanctum'])->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('User/Userlist',[UserController::class,'UserList']);

    Route::post('/logout', [AuthController::class, 'logout']);
});