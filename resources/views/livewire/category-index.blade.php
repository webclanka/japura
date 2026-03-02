<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">📂 Quiz Categories</h1>
            <p class="text-gray-500 dark:text-gray-400">Choose a category to start learning</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('categories.show', $category->slug) }}" wire:navigate class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:border-indigo-300 transition group">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="text-5xl">{{ $category->icon }}</div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-indigo-600">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $category->published_quizzes_count }} quizzes</p>
                    </div>
                </div>
                @if($category->description)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $category->description }}</p>
                @endif
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-400">{{ $completedByCategory[$category->id] ?? 0 }} completed</span>
                    <span class="text-indigo-600 text-sm font-medium group-hover:translate-x-1 transition">Explore →</span>
                </div>
            </a>
            @endforeach
        </div>

        @if($categories->isEmpty())
        <div class="text-center py-12">
            <div class="text-5xl mb-4">📂</div>
            <p class="text-gray-500">No categories available yet. Check back soon!</p>
        </div>
        @endif
    </div>
</div>
