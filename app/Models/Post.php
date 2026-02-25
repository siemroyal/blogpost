<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $casts = [
        "published_at" => 'datetime'
    ];
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'post_image',
        'excerpt',
        'body',
        'status',
        'published_at',
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%$search%")
                    ->orWhere('body', 'like', "%$search%");
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category_id', $category);
    }

    public function scopewithUserAndCategory($query)
    {
        return $query->with(['category:id,name', 'user:id,name']);
    }

    protected static function booted()
    {
        static::forceDeleted(function ($post) {
            if ($post->post_image && Storage::disk('public')->exists($post->post_image)) {
                Storage::disk('public')->delete($post->post_image);
            }
        });
    }
    /*
    Authentication vs Authorization
        + Authentication: Verifying user identity (login)
            - Registration
            - Login
            - Password Reset
            - Logout
            - Email Verification
            - Two-Factor Authentication
            - Social Login
            - Remember Me
            1. Packages: Laravel Breeze, Jetstream, Fortify, Socialite, Laravel UI, Sanctum,
                Passport, etc.
                Laravel Roles/Permissions Packages:
                - Spatie Laravel-Permission
                - Bouncer
                - Laratrust
                - Sentinel
                - Entrust
            2. Manual Implementation
                - User Model
                - Auth Controllers
                - Middleware
                - Views
        + Authorization: Permissions (what user can do)
            - Role-Based Access Control (RBAC)
            - Permission-Based Access Control
            - Policy-Based Authorization
            - Gates
            - Middleware
            + Packages: Spatie Laravel-Permission, Bouncer, Laratrust, Sentinel, Entrust, etc.
            + Manual Implementation

    */

}
