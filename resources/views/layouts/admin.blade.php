<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false }" :class="{ 'dark': darkMode }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name', 'Japura Quiz') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-indigo-700 dark:bg-indigo-900 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6 border-b border-indigo-600">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <span class="text-2xl">🎓</span>
                    <div>
                        <div class="font-bold text-lg">Japura Quiz</div>
                        <div class="text-xs text-indigo-200">Admin Panel</div>
                    </div>
                </a>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-indigo-600 transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600' : '' }}">
                    <span>📊</span><span>Dashboard</span>
                </a>
                <a href="{{ route('admin.categories') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-indigo-600 transition {{ request()->routeIs('admin.categories') ? 'bg-indigo-600' : '' }}">
                    <span>📂</span><span>Categories</span>
                </a>
                <a href="{{ route('admin.quizzes') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-indigo-600 transition {{ request()->routeIs('admin.quizzes') ? 'bg-indigo-600' : '' }}">
                    <span>📝</span><span>Quizzes</span>
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-indigo-600 transition {{ request()->routeIs('admin.users') ? 'bg-indigo-600' : '' }}">
                    <span>👥</span><span>Users</span>
                </a>
                <div class="border-t border-indigo-600 my-2 pt-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-indigo-600 transition">
                        <span>🏠</span><span>Back to App</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Admin Panel</h1>
                <div class="flex items-center space-x-3">
                    <button @click="darkMode = !darkMode" class="p-2 rounded-md text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        <svg x-show="darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </button>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ auth()->user()->name }}</span>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
