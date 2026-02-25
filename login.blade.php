<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Secure Access</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .bg-pattern {
            background-color: #f8fafc;
            background-image: radial-gradient(#e2e8f0 0.5px, transparent 0.5px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-5xl flex flex-col md:flex-row shadow-2xl rounded-3xl overflow-hidden bg-white border border-slate-200">

        <!-- Left Side: Visual/Branding -->
        <div class="hidden md:flex md:w-1/2 bg-indigo-600 p-12 flex-col justify-between text-white relative overflow-hidden">
            <!-- Decorative Circles -->
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-500 rounded-full opacity-50"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-indigo-700 rounded-full opacity-50"></div>

            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-8">
                    <div class="bg-white/20 p-2 rounded-lg backdrop-blur-md">
                        <i data-lucide="layers" class="w-8 h-8 text-white"></i>
                    </div>
                    <span class="text-2xl font-extrabold tracking-tight">PLATFORM<span class="font-light text-indigo-200">CMS</span></span>
                </div>
                <h1 class="text-4xl font-extrabold leading-tight mb-6">Elevate your <br>content experience.</h1>
                <p class="text-indigo-100 text-lg leading-relaxed max-w-sm">
                    Access your personalized dashboard to manage posts, users, and platform settings.
                </p>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10">
                    <div class="flex -space-x-3">
                        <img src="https://i.pravatar.cc/100?u=1" class="w-10 h-10 rounded-full border-2 border-indigo-600" alt="">
                        <img src="https://i.pravatar.cc/100?u=2" class="w-10 h-10 rounded-full border-2 border-indigo-600" alt="">
                        <img src="https://i.pravatar.cc/100?u=3" class="w-10 h-10 rounded-full border-2 border-indigo-600" alt="">
                    </div>
                    <p class="text-sm font-medium">Joined by 2,000+ editors worldwide.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 p-8 lg:p-16 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">

                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-2">Welcome Back</h2>
                    <p class="text-slate-500 font-medium">Please enter your details to sign in.</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </div>
                            <input type="email" name="email" id="email"
                                class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('email') border-red-500 @enderror"
                                placeholder="name@company.com" value="{{ old('email') }}" required autofocus>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                            <a href="#" class="text-sm font-bold text-indigo-600 hover:text-indigo-500 transition-colors">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i data-lucide="lock" class="w-5 h-5"></i>
                            </div>
                            <input type="password" name="password" id="password"
                                class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                placeholder="••••••••" required>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember & Logic -->
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded cursor-pointer">
                        <label for="remember" class="ml-3 block text-sm font-medium text-slate-600 cursor-pointer">
                            Keep me signed in for 30 days
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-200 transition-all duration-300 active:scale-[0.98] flex items-center justify-center gap-2">
                        <span>Sign into Dashboard</span>
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-10 pt-8 border-t border-slate-100 text-center">
                    <p class="text-sm text-slate-500 font-medium">
                        New to the platform?
                        <a href="mailto:admin@example.com" class="text-indigo-600 font-bold hover:underline">Request an account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Lucide Icon Initialization -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
