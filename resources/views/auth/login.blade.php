<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'Project Management') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 font-sans antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <!-- Logo & Header -->
        <div class="text-center mb-6 sm:mb-8 w-full max-w-md">
        
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">
                PT. INDONESIA GADAI OKE
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-2">Masuk ke dashboard untuk mengelola proyek internal</p>
        </div>

        <!-- Login Form Card -->
        <div class="w-full max-w-md">
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 sm:mb-6 font-medium text-xs sm:text-sm text-green-600 bg-green-50 p-3 sm:p-4 rounded-lg border border-green-200">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block font-medium text-sm sm:text-base text-gray-700 mb-1 sm:mb-2">
                                Email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input id="email" 
                                       class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm sm:text-base transition-all @error('email') border-red-300 @enderror" 
                                       type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="nama@email.com"
                                       required 
                                       autofocus 
                                       autocomplete="username" />
                            </div>
                            @error('email')
                                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block font-medium text-sm sm:text-base text-gray-700 mb-1 sm:mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input id="password" 
                                       class="block w-full pl-10 pr-3 py-2.5 sm:py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm sm:text-base transition-all @error('password') border-red-300 @enderror"
                                       type="password"
                                       name="password"
                                       placeholder="••••••••"
                                       required 
                                       autocomplete="current-password" />
                            </div>
                            @error('password')
                                <p class="mt-1.5 sm:mt-2 text-xs sm:text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-0">
                            <div class="flex items-center">
                                <input id="remember_me" 
                                       type="checkbox" 
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" 
                                       name="remember">
                                <label for="remember_me" class="ml-2 block text-xs sm:text-sm text-gray-700">
                                    Ingat saya
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-xs sm:text-sm">
                                    <a class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors" href="{{ route('password.request') }}">
                                        Lupa password?
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                    class="group relative w-full flex justify-center items-center py-2.5 sm:py-3 px-4 border border-transparent text-sm sm:text-base font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-md hover:shadow-lg">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <svg class="h-5 w-5 text-indigo-400 group-hover:text-indigo-300 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                Masuk ke Dashboard
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Demo Credentials Section -->
                <div class="px-4 sm:px-6 lg:px-8 pb-6 sm:pb-8">
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4 sm:p-5 border border-indigo-100">
                        <div class="flex items-start sm:items-center gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs sm:text-sm font-semibold text-gray-900 mb-2">Akun Demo</h4>
                                <div class="text-xs sm:text-sm text-gray-700 space-y-1">
                                    <div class="flex flex-col sm:flex-row sm:gap-2">
                                        <span class="font-medium text-gray-900">Email:</span>
                                        <span class="text-gray-600 break-all">ptigo.karir@gmail.com</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:gap-2">
                                        <span class="font-medium text-gray-900">Password:</span>
                                        <span class="text-gray-600">password123</span>
                                    </div>
                                </div>
                                <button type="button" 
                                        onclick="fillDemoCredentials()" 
                                        class="mt-3 inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 border border-transparent text-xs sm:text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-sm hover:shadow">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Isi Otomatis
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Text -->
            <p class="mt-6 sm:mt-8 text-center text-xs sm:text-sm text-gray-500">
                © 2025 PT. Indonesia Gadai Oke. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        function fillDemoCredentials() {
            document.getElementById('email').value = 'ptigo.karir@gmail.com';
            document.getElementById('password').value = 'password123';
            
            // Optional: Auto focus on submit button
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.focus();
            }
        }
    </script>
</body>
</html>