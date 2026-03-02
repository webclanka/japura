<div class="py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('categories.index') }}" wire:navigate class="text-indigo-600 text-sm hover:underline">← Back to Categories</a>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 mt-4 text-center">
            <div class="text-5xl mb-3">🏆</div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $attempt->quiz->title }}</h1>
            <p class="text-gray-500 mb-6">Quiz Results</p>

            <!-- Score -->
            <div class="text-6xl font-bold text-indigo-600 mb-2">{{ $attempt->score }}%</div>
            <div class="flex justify-center space-x-1 mb-6">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $attempt->star_rating)
                        <span class="text-2xl text-amber-400">⭐</span>
                    @else
                        <span class="text-2xl text-gray-300">☆</span>
                    @endif
                @endfor
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                <div class="bg-green-50 dark:bg-gray-700 rounded-xl p-3">
                    <div class="text-xl font-bold text-green-600">{{ $attempt->correct_answers }}</div>
                    <div class="text-xs text-gray-500">Correct</div>
                </div>
                <div class="bg-red-50 dark:bg-gray-700 rounded-xl p-3">
                    <div class="text-xl font-bold text-red-500">{{ $attempt->wrong_answers }}</div>
                    <div class="text-xs text-gray-500">Wrong</div>
                </div>
                <div class="bg-amber-50 dark:bg-gray-700 rounded-xl p-3">
                    <div class="text-xl font-bold text-amber-500">{{ $attempt->total_points_earned }}</div>
                    <div class="text-xs text-gray-500">Points</div>
                </div>
                <div class="bg-indigo-50 dark:bg-gray-700 rounded-xl p-3">
                    <div class="text-xl font-bold text-indigo-600">{{ $attempt->time_taken_seconds ?? 0 }}s</div>
                    <div class="text-xs text-gray-500">Time</div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center mb-8">
                <a href="{{ route('quiz.play', $attempt->quiz->slug) }}" wire:navigate class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-xl transition">🔄 Retry Quiz</a>
                <a href="{{ route('categories.show', $attempt->quiz->category->slug) }}" wire:navigate class="border border-gray-300 text-gray-600 hover:bg-gray-50 font-bold py-2 px-6 rounded-xl transition">�� More Quizzes</a>
            </div>
        </div>

        <!-- Question Review -->
        <div class="mt-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Question Review</h2>
            @foreach($attempt->responses as $response)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-sm font-medium text-gray-800 dark:text-white flex-1">{{ $response->question->question_text }}</span>
                    <span class="{{ $response->is_correct ? 'text-green-500' : 'text-red-500' }} text-lg ml-2">{{ $response->is_correct ? '✓' : '✗' }}</span>
                </div>
                <div class="space-y-1">
                    @foreach($response->question->options as $option)
                    <div class="text-sm px-3 py-1 rounded {{ $option->is_correct ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400' : ($response->option_id === $option->id && !$response->is_correct ? 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400' : 'text-gray-600 dark:text-gray-400') }}">
                        {{ $option->option_text }}
                        @if($option->is_correct) <span class="ml-1 text-xs">(correct)</span> @endif
                    </div>
                    @endforeach
                </div>
                @if($response->question->explanation)
                <div class="mt-2 text-xs text-gray-500 italic">💡 {{ $response->question->explanation }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
