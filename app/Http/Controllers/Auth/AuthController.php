<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function index(Request $request){
        $usersCount = User::count(); //SELECT COUNT(*) FROM users
        $activeCount = User::where('is_active', true)->count(); //select count(*) from users where is_active = true
        $adminCount = User::where('role', 'admin')->count(); //select count(*) from users where role = 'admin'
        $newUsersCount = User::whereMonth('created_at', now()->month)->count(); //select count(*) from users where MONTH(created_at) = MONTH(CURRENT_DATE())
        $users = User::query()
            ->when($request->search, fn($q) =>
            $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->role, fn($q) =>
            $q->where('role', $request->role))
            ->when($request->has('status'), fn($q) =>
            $q->where('is_active', $request->status))
            ->paginate(10);

        return view('auth.index', compact('users', 'usersCount', 'activeCount', 'adminCount', 'newUsersCount'));

    }
    public function show(User $user){
        return view('auth.show', compact('user'));
    }
    public function edit(User $user){
        return view('auth.edit', compact('user'));
    }
    public function update(Request $request, User $user)
    {
        // 1. Validate Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
            'is_active' => 'nullable|boolean',
            'role' => 'required|in:admin,editor,viewer,contributor',
        ]);

        // 2. Handle Profile Picture
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('users', $filename, 'public');
            $user->profile_picture = $path;
        }

        // 3. Update Basic Info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // 4. Handle Password only if filled
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        // 5. Handle Checkbox (If missing from request, it means it's unchecked/false)
        $user->is_active = $request->has('is_active');

        $user->save();
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    public function destroy(User $user){
        // 1. Check if user has profile picture and delete it
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        // 2. Delete User
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function login(Request $request){
        //Login logic here
        // $credentials = $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required|string',
        // ]);
        // $user = User::where('email', $credentials['email'])->first();
        // if ($user && Hash::check($credentials['password'], $user->password)) {
        //     // Authentication passed
        //     $request->session()->put('user_id', $user->id);
        //     return redirect()->route('users.index')->with('success', 'Logged in successfully.');
        // } else {
        //     return back()->withErrors(['email' => 'Invalid credentials.']);
        // }
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $email = $credentials['email'];
        $password = $credentials['password'];

        if (Auth::attempt(["email" => $email, "password" => $password, "is_active" => 1])) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function logout(Request $request){
        // Logout logic here
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
    public function register_form(){
        return view('auth.register');
    }
    public function register(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required|string|min:6|confirmed',
            'is_active' => 'required|boolean',
            'role' => 'required|in:admin,user,editor,viewer,contributor',
        ]);
        $user = new User();
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('users', $filename, 'public');
            $user->profile_picture = 'users/' . $filename;
        }
        $user->name = $request->name;
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->is_active = $validated['is_active'];
        $user->role = $validated['role'];
        $user->save();
        return redirect()->route('users.index')->with('success', 'User registered successfully.');
    }
    public function login_form(){
        return view('auth.login');
    }
}
