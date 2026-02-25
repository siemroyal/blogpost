<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\backend\PostController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth','checkUserRole:admin,user,viewer,editor,contributor'])
->group(function(){
    // Authentication routes
    Route::get("register",[AuthController::class,'register_form'])->name('register');
    Route::resource('activity-logs', ActivityLogController::class);
    Route::resource('categories',CategoryController::class);
    Route::resource('posts',PostController::class);
    Route::get('posts-trash', [PostController::class, 'trash'])
    ->name('posts.trash');
    Route::post('posts/{id}/restore', [PostController::class, 'restore'])
        ->name('posts.restore');
    Route::delete('posts/{id}/force-delete', [PostController::class, 'forceDelete'])
        ->name('posts.forceDelete');

    Route::post("register",[AuthController::class,'register'])->name(name: 'register');
    Route::post("logout",[AuthController::class,'logout'])->name(name: 'logout');
    Route::resource('users', AuthController::class)
    ->except(['create', 'store']);
    Route::get('/dashboard', function () {
    return view('dashboard');})->name('dashboard');

});
Route::middleware(['guest'])
->group(function(){
    //Login And Logout
    Route::get("login",[AuthController::class,'login_form'])->name('login');
    Route::post("login",[AuthController::class,'login'])->name(name: 'login');

});
