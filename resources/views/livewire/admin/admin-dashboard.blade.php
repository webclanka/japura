<div>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">📊 Admin Dashboard</h1>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm">
            <div class="text-3xl font-bold text-indigo-600">{{ $totalUsers }}</div>
            <div class="text-sm text-gray-500">Total Users</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm">
            <div class="text-3xl font-bold text-green-600">{{ $totalQuizzes }}</div>
            <div class="text-sm text-gray-500">Total Quizzes</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm">
            <div class="text-3xl font-bold text-amber-500">{{ $totalCategories }}</div>
            <div class="text-sm text-gray-500">Categories</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm">
            <div class="text-3xl font-bold text-purple-600">{{ $totalAttempts }}</div>
            <div class="text-sm text-gray-500">Completed Attempts</div>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('admin.categories') }}" class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm hover:shadow-md transition text-center">
            <div class="text-3xl mb-2">📂</div><div class="font-medium text-gray-800 dark:text-white">Manage Categories</div>
        </a>
        <a href="{{ route('admin.quizzes') }}" class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm hover:shadow-md transition text-center">
            <div class="text-3xl mb-2">📝</div><div class="font-medium text-gray-800 dark:text-white">Manage Quizzes</div>
        </a>
        <a href="{{ route('admin.users') }}" class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm hover:shadow-md transition text-center">
            <div class="text-3xl mb-2">👥</div><div class="font-medium text-gray-800 dark:text-white">Manage Users</div>
        </a>
    </div>
</div>
