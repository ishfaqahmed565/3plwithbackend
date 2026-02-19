<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3PL Logistics Portal - Unified Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-blue-600">3PL Portal</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600 text-sm">Warehouse Management System</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Left Side - Features -->
            <div>
                <div class="mb-12">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        Welcome to 3PL Logistics Portal
                    </h2>
                    <p class="text-xl text-gray-600">
                        Streamline your warehouse operations with our comprehensive third-party logistics management system. 
                        Track orders, manage inventory, and coordinate deliveries all in one place.
                    </p>
                </div>

                <!-- Features Section -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Key Features</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">Order Tracking</h4>
                            <p class="text-sm text-gray-600">Real-time order status updates through the complete lifecycle</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">Inventory Management</h4>
                            <p class="text-sm text-gray-600">Efficient rack assignment and stock tracking system</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">Notifications</h4>
                            <p class="text-sm text-gray-600">Instant alerts for order status changes and updates</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-2">Settlement Reports</h4>
                            <p class="text-sm text-gray-600">Transparent pricing and settlement calculations</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md">
                    <div class="mb-8 text-center">
                        <h3 class="text-3xl font-bold text-blue-600 mb-2">3PL Portal Login</h3>
                        <p class="text-gray-600">Login to your account to continue</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="mb-6">
                            <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200">
                            Login
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-xs text-gray-500">Login with your account credentials to access your portal</p>
                    </div>

                    <div class="mt-8 pt-6 border-t">
                        <p class="text-sm text-gray-600 text-center mb-4">User Type Icons:</p>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div class="text-xs">
                                <div class="w-8 h-8 bg-green-100 rounded-full mx-auto mb-1 flex items-center justify-center">
                                    <span class="text-green-600 font-bold">C</span>
                                </div>
                                <p class="text-gray-600">Client</p>
                            </div>
                            <div class="text-xs">
                                <div class="w-8 h-8 bg-purple-100 rounded-full mx-auto mb-1 flex items-center justify-center">
                                    <span class="text-purple-600 font-bold">A</span>
                                </div>
                                <p class="text-gray-600">Agent</p>
                            </div>
                            <div class="text-xs">
                                <div class="w-8 h-8 bg-blue-100 rounded-full mx-auto mb-1 flex items-center justify-center">
                                    <span class="text-blue-600 font-bold">D</span>
                                </div>
                                <p class="text-gray-600">Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p class="text-sm text-gray-400">
                    © 2026 3PL Logistics Portal. All rights reserved.
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    Laravel {{ app()->version() }} - Warehouse Management System
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
                     
</body>
</html>
