<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('categories.index') }}" wire:navigate class="text-indigo-600 text-sm hover:underline">← Back to Categories</a>
            <div class="flex items-center space-x-4 mt-4">
                <div class="text-5xl">{{ $category->icon }}</div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="text-gray-500 dark:text-gray-400">{{ $category->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        @if($quizzes->isEmpty())
        <div class="text-center py-12">
            <div class="text-5xl mb-4">📝</div>
            <p class="text-gray-500">No quizzes available in this category yet.</p>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($quizzes as $quiz)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="font-bold text-gray-800 dark:text-white">{{ $quiz->title }}</h3>
                        <span class="text-xs px-2 py-1 rounded-full font-medium
                            {{ $quiz->difficulty === 'easy' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $quiz->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $quiz->difficulty === 'hard' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($quiz->difficulty) }}
                        </span>
                    </div>
                    @if($quiz->description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $quiz->description }}</p>
                    @endif
                    <div class="flex items-center space-x-4 text-xs text-gray-500 mb-4">
                        <span>📝 {{ $quiz->total_questions }} questions</span>
                        <span>⏱️ ~{{ round($quiz->estimated_time / 60) }} min</span>
                        <span>🏆 {{ $quiz->max_points }} pts max</span>
                    </div>
                    @if(isset($bestScores[$quiz->id]) && $bestScores[$quiz->id])
                        <div class="text-xs text-green-600 mb-3">✓ Best: {{ $bestScores[$quiz->id]->score }}% ({{ $bestScores[$quiz->id]->total_points_earned }} pts)</div>
                    @endif
                </div>
                <div class="px-6 pb-4">
                    <a href="{{ route('quiz.play', $quiz->slug) }}" wire:navigate class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition">
                        {{ isset($bestScores[$quiz->id]) && $bestScores[$quiz->id] ? 'Retry Quiz' : 'Start Quiz' }} →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
