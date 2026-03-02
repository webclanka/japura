<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-gray-500 dark:text-gray-400">Keep learning and climb the leaderboard</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">Total Points</span>
                    <span class="text-2xl">🏆</span>
                </div>
                <div class="text-2xl font-bold text-indigo-600">{{ number_format(auth()->user()->total_points) }}</div>
                <div class="text-xs text-gray-400">Level {{ auth()->user()->level }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">Current Streak</span>
                    <span class="text-2xl">🔥</span>
                </div>
                <div class="text-2xl font-bold text-orange-500">{{ auth()->user()->current_streak }}</div>
                <div class="text-xs text-gray-400">days</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">Quizzes Done</span>
                    <span class="text-2xl">📚</span>
                </div>
                <div class="text-2xl font-bold text-green-600">{{ $completedCount }}</div>
                <div class="text-xs text-gray-400">completed</div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-500">Rank</span>
                    <span class="text-2xl">🎯</span>
                </div>
                <div class="text-2xl font-bold text-purple-600">#{{ auth()->user()->rank }}</div>
                <div class="text-xs text-gray-400">global rank</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Categories -->
            <div class="lg:col-span-2">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">📂 Browse Categories</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" wire:navigate class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md hover:border-indigo-300 transition group">
                        <div class="text-3xl mb-2">{{ $category->icon }}</div>
                        <div class="font-medium text-gray-800 dark:text-white text-sm group-hover:text-indigo-600">{{ $category->name }}</div>
                        <div class="text-xs text-gray-400">{{ $category->published_quizzes_count }} quizzes</div>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">📈 Recent Activity</h2>
                @if($recentAttempts->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 text-center text-gray-500 border border-gray-100 dark:border-gray-700">
                        <div class="text-4xl mb-2">🎮</div>
                        <p>No quizzes played yet.</p>
                        <a href="{{ route('categories.index') }}" wire:navigate class="text-indigo-600 text-sm font-medium mt-2 block">Start playing →</a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($recentAttempts as $attempt)
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 shadow-sm border border-gray-100 dark:border-gray-700">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-medium text-sm text-gray-800 dark:text-white">{{ $attempt->quiz->title }}</div>
                                    <div class="text-xs text-gray-400">{{ $attempt->created_at->diffForHumans() }}</div>
                                </div>
                                @if($attempt->is_completed)
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">{{ $attempt->score }}%</span>
                                @else
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">In Progress</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
