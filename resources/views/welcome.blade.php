<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Japura Quiz - Gamified Learning Platform</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-3xl">🎓</span>
                    <span class="font-bold text-xl text-indigo-600">Japura Quiz</span>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-6xl mb-6">🎓</div>
            <h1 class="text-4xl sm:text-6xl font-bold mb-6">Japura Quiz</h1>
            <p class="text-xl sm:text-2xl text-indigo-200 mb-4">Gamified Learning Platform</p>
            <p class="text-lg text-indigo-300 mb-10 max-w-2xl mx-auto">Test your knowledge, earn points, climb the leaderboard, and unlock achievements. Built for students of University of Sri Jayewardenepura.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('categories.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-8 py-4 rounded-xl text-lg transition transform hover:scale-105">Start Playing →</a>
                @else
                    <a href="{{ route('register') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-8 py-4 rounded-xl text-lg transition transform hover:scale-105">Start Playing Free →</a>
                    <a href="{{ route('login') }}" class="border-2 border-white text-white font-bold px-8 py-4 rounded-xl text-lg hover:bg-white hover:text-indigo-600 transition">Sign In</a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Stats Bar -->
    <section class="bg-indigo-50 py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
                <div><div class="text-3xl font-bold text-indigo-600">5+</div><div class="text-gray-600">Categories</div></div>
                <div><div class="text-3xl font-bold text-indigo-600">50+</div><div class="text-gray-600">Quizzes</div></div>
                <div><div class="text-3xl font-bold text-indigo-600">500+</div><div class="text-gray-600">Questions</div></div>
                <div><div class="text-3xl font-bold text-indigo-600">10</div><div class="text-gray-600">Achievements</div></div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Why Japura Quiz?</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-xl border border-gray-100 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">⚡</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Speed Bonuses</h3>
                    <p class="text-gray-600">Answer faster to earn bonus points. Every second counts!</p>
                </div>
                <div class="text-center p-6 rounded-xl border border-gray-100 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">��</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Leaderboards</h3>
                    <p class="text-gray-600">Compete with fellow students on global and weekly rankings.</p>
                </div>
                <div class="text-center p-6 rounded-xl border border-gray-100 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">🔥</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Daily Streaks</h3>
                    <p class="text-gray-600">Maintain your learning streak and earn special achievements.</p>
                </div>
                <div class="text-center p-6 rounded-xl border border-gray-100 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">🎯</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Instant Feedback</h3>
                    <p class="text-gray-600">Learn from every answer with detailed explanations.</p>
                </div>
                <div class="text-center p-6 rounded-xl border border-gray-100 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">📚</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Multiple Categories</h3>
                    <p class="text-gray-600">From General Knowledge to Sri Lankan History and Mathematics.</p>
                </div>
                <div class="text-center p-6 rounded-xl border border-gray-100 hover:shadow-lg transition">
                    <div class="text-5xl mb-4">🥇</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Achievements</h3>
                    <p class="text-gray-600">Unlock 10+ badges as you progress through your learning journey.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-700 py-16 text-white text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Test Your Knowledge?</h2>
        <p class="text-indigo-200 mb-8 text-lg">Join students from University of Sri Jayewardenepura</p>
        @guest
            <a href="{{ route('register') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-10 py-4 rounded-xl text-lg transition transform hover:scale-105">Join Now — It's Free!</a>
        @endguest
        @auth
            <a href="{{ route('categories.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-10 py-4 rounded-xl text-lg transition transform hover:scale-105">Continue Playing →</a>
        @endauth
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-8 text-center">
        <p>© {{ date('Y') }} Japura Quiz — University of Sri Jayewardenepura</p>
    </footer>
</body>
</html>
