<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">🏅 Achievements</h1>
            <p class="text-gray-500 dark:text-gray-400">Unlock achievements as you progress</p>
            <div class="mt-2 text-sm text-indigo-600 font-medium">{{ count($earnedIds) }} / {{ $achievements->count() }} unlocked</div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($achievements as $achievement)
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border {{ in_array($achievement->id, $earnedIds) ? 'border-amber-300 dark:border-amber-600' : 'border-gray-100 dark:border-gray-700 opacity-70' }} transition">
                <div class="flex items-center space-x-3 mb-3">
                    <span class="text-4xl {{ in_array($achievement->id, $earnedIds) ? '' : 'grayscale' }}">{{ $achievement->icon }}</span>
                    <div>
                        <div class="font-bold text-gray-800 dark:text-white">{{ $achievement->name }}</div>
                        @if(in_array($achievement->id, $earnedIds))
                        <div class="text-xs text-amber-500 font-medium">✓ Earned!</div>
                        @else
                        <div class="text-xs text-gray-400">Locked</div>
                        @endif
                    </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $achievement->description }}</p>
                @if(!in_array($achievement->id, $earnedIds))
                <div class="mt-2 text-xs text-gray-400">
                    @if($achievement->requirement_type === 'quizzes_completed')
                        Progress: {{ $user->completedAttempts()->count() }} / {{ $achievement->requirement_value }} quizzes
                    @elseif($achievement->requirement_type === 'streak_days')
                        Progress: {{ $user->current_streak }} / {{ $achievement->requirement_value }} days
                    @elseif($achievement->requirement_type === 'points_earned')
                        Progress: {{ number_format($user->total_points) }} / {{ number_format($achievement->requirement_value) }} points
                    @endif
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
