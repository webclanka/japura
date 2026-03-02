<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-4">
    <div class="max-w-3xl mx-auto px-4">

        {{-- START STATE --}}
        @if($state === 'start')
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 text-center">
            <div class="text-6xl mb-4">🎮</div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $quiz->title }}</h1>
            @if($quiz->description)
                <p class="text-gray-500 mb-6">{{ $quiz->description }}</p>
            @endif
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-indigo-50 dark:bg-gray-700 rounded-xl p-4">
                    <div class="text-2xl font-bold text-indigo-600">{{ $quiz->total_questions }}</div>
                    <div class="text-xs text-gray-500">Questions</div>
                </div>
                <div class="bg-amber-50 dark:bg-gray-700 rounded-xl p-4">
                    <div class="text-2xl font-bold text-amber-500">{{ $quiz->time_limit_per_question }}s</div>
                    <div class="text-xs text-gray-500">Per Question</div>
                </div>
                <div class="bg-green-50 dark:bg-gray-700 rounded-xl p-4">
                    <div class="text-2xl font-bold text-green-600">{{ $quiz->max_points }}</div>
                    <div class="text-xs text-gray-500">Max Points</div>
                </div>
            </div>
            <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 mb-8">
                <span>Difficulty:</span>
                <span class="px-3 py-1 rounded-full font-medium
                    {{ $quiz->difficulty === 'easy' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $quiz->difficulty === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $quiz->difficulty === 'hard' ? 'bg-red-100 text-red-700' : '' }}">
                    {{ ucfirst($quiz->difficulty) }}
                </span>
            </div>
            <button wire:click="startQuiz" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-10 rounded-xl text-lg transition transform hover:scale-105">
                🚀 Start Quiz!
            </button>
            <div class="mt-4">
                <a href="{{ route('categories.index') }}" wire:navigate class="text-gray-400 text-sm hover:text-gray-600">← Back to categories</a>
            </div>
        </div>

        {{-- PLAYING STATE --}}
        @elseif($state === 'playing' || $state === 'feedback')
        <div
            x-data="{
                timer: {{ $quiz->time_limit_per_question }},
                interval: null,
                startTimer() {
                    clearInterval(this.interval);
                    this.timer = {{ $quiz->time_limit_per_question }};
                    this.interval = setInterval(() => {
                        this.timer--;
                        if (this.timer <= 0) {
                            clearInterval(this.interval);
                            @this.dispatch('time-up');
                        }
                    }, 1000);
                },
                stopTimer() {
                    clearInterval(this.interval);
                }
            }"
            x-init="{{ $state === 'playing' ? 'startTimer()' : 'stopTimer()' }}"
            @question-changed.window="startTimer()"
        >
            <!-- Progress Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 mb-4 flex items-center space-x-4">
                <span class="text-sm text-gray-500">{{ $currentIndex + 1 }} / {{ $totalQuestions }}</span>
                <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ (($currentIndex) / max(1, $totalQuestions)) * 100 }}%"></div>
                </div>
                <span class="text-sm font-bold text-amber-500">{{ $totalPoints }} pts</span>
            </div>

            <!-- Question Card -->
            @if($currentQuestion)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 mb-4">
                <!-- Timer -->
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm text-gray-400">Question {{ $currentIndex + 1 }}</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full transition-all" :style="'width: ' + (timer / {{ $quiz->time_limit_per_question }} * 100) + '%'" :class="{ 'bg-red-500': timer <= 5 }"></div>
                        </div>
                        <span class="text-sm font-bold" :class="timer <= 5 ? 'text-red-500' : 'text-gray-600'" x-text="timer + 's'"></span>
                    </div>
                </div>

                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">{{ $currentQuestion->question_text }}</h2>

                <!-- Options -->
                <div class="space-y-3">
                    @foreach($currentQuestion->options as $option)
                    <button
                        wire:click="selectOption({{ $option->id }})"
                        @if($state === 'feedback') disabled @endif
                        class="w-full text-left p-4 rounded-xl border-2 transition font-medium
                            @if($state === 'feedback')
                                @if($option->id === $correctOptionId) border-green-500 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400
                                @elseif($option->id === $selectedOptionId && !$isCorrect) border-red-500 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400
                                @else border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400
                                @endif
                            @else
                                border-gray-200 dark:border-gray-600 text-gray-800 dark:text-white hover:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 cursor-pointer
                            @endif
                        ">
                        <span class="inline-block w-6 h-6 rounded-full border-2 border-current text-center text-xs leading-5 mr-2">{{ chr(65 + $loop->index) }}</span>
                        {{ $option->option_text }}
                        @if($state === 'feedback')
                            @if($option->id === $correctOptionId) <span class="float-right">✓</span>
                            @elseif($option->id === $selectedOptionId && !$isCorrect) <span class="float-right">✗</span>
                            @endif
                        @endif
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Feedback -->
            @if($state === 'feedback')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 mb-4">
                @if($isCorrect)
                    <div class="text-green-600 font-bold mb-2">✅ Correct!</div>
                @elseif($selectedOptionId === null)
                    <div class="text-red-600 font-bold mb-2">⏰ Time's up!</div>
                @else
                    <div class="text-red-600 font-bold mb-2">❌ Incorrect!</div>
                @endif
                @if($currentQuestion->explanation)
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $currentQuestion->explanation }}</p>
                @endif
                <button wire:click="nextQuestion" class="mt-3 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-xl transition">
                    {{ $currentIndex + 1 >= $totalQuestions ? '🏁 See Results' : 'Next Question →' }}
                </button>
            </div>
            @endif
            @endif
        </div>

        {{-- FINISHED STATE --}}
        @elseif($state === 'finished')
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8 text-center">
            <div class="text-6xl mb-4">🎉</div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Quiz Complete!</h2>
            <div class="grid grid-cols-3 gap-4 my-6">
                <div class="bg-green-50 dark:bg-gray-700 rounded-xl p-4">
                    <div class="text-2xl font-bold text-green-600">{{ $correctAnswers }}</div>
                    <div class="text-xs text-gray-500">Correct</div>
                </div>
                <div class="bg-amber-50 dark:bg-gray-700 rounded-xl p-4">
                    <div class="text-2xl font-bold text-amber-500">{{ $totalPoints }}</div>
                    <div class="text-xs text-gray-500">Points Earned</div>
                </div>
                <div class="bg-red-50 dark:bg-gray-700 rounded-xl p-4">
                    <div class="text-2xl font-bold text-red-500">{{ $wrongAnswers }}</div>
                    <div class="text-xs text-gray-500">Wrong</div>
                </div>
            </div>
            @if($attemptId)
            <a href="{{ route('quiz.results', [$slug, $attemptId]) }}" wire:navigate class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl transition inline-block mb-3">
                📊 View Detailed Results
            </a>
            @endif
            <div>
                <a href="{{ route('categories.index') }}" wire:navigate class="text-gray-400 text-sm hover:text-gray-600">← Back to categories</a>
            </div>
        </div>
        @endif
    </div>
</div>
