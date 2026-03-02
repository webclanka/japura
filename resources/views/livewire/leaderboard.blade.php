<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">🏆 Leaderboard</h1>
            <p class="text-gray-500 dark:text-gray-400">Top performers in the Japura Quiz community</p>
        </div>

        <!-- Tabs -->
        <div class="flex space-x-2 mb-6 bg-gray-100 dark:bg-gray-800 p-1 rounded-xl w-fit">
            <button wire:click="$set('activeTab', 'global')" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $activeTab === 'global' ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow' : 'text-gray-500 hover:text-gray-700' }}">Global</button>
            <button wire:click="$set('activeTab', 'weekly')" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $activeTab === 'weekly' ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow' : 'text-gray-500 hover:text-gray-700' }}">Weekly</button>
            <button wire:click="$set('activeTab', 'category')" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $activeTab === 'category' ? 'bg-white dark:bg-gray-700 text-indigo-600 shadow' : 'text-gray-500 hover:text-gray-700' }}">By Category</button>
        </div>

        @if($activeTab === 'category')
        <div class="mb-4">
            <select wire:model.live="categoryId" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-800 dark:text-white">
                <option value="">Select a category</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Leaderboard Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            @forelse($users as $index => $user)
            <div class="flex items-center px-6 py-4 border-b border-gray-100 dark:border-gray-700 last:border-0 {{ $user->id === $currentUser->id ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                <div class="w-10 text-center font-bold {{ $index === 0 ? 'text-amber-500 text-xl' : ($index === 1 ? 'text-gray-400 text-lg' : ($index === 2 ? 'text-orange-400 text-lg' : 'text-gray-400')) }}">
                    {{ $index === 0 ? '🥇' : ($index === 1 ? '🥈' : ($index === 2 ? '🥉' : $index + 1)) }}
                </div>
                <div class="flex-1 ml-4">
                    <div class="font-medium text-gray-900 dark:text-white flex items-center">
                        {{ $user->name }}
                        @if($user->id === $currentUser->id) <span class="ml-2 text-xs bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full">You</span> @endif
                    </div>
                    <div class="text-xs text-gray-500">Level {{ $user->level }}</div>
                </div>
                <div class="text-right">
                    @if($activeTab === 'global')
                        <div class="font-bold text-indigo-600">{{ number_format($user->total_points) }}</div>
                    @else
                        <div class="font-bold text-indigo-600">{{ number_format($user->quiz_attempts_sum_total_points_earned ?? 0) }}</div>
                    @endif
                    <div class="text-xs text-gray-400">points</div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <div class="text-4xl mb-2">🏆</div>
                <p>No data available yet</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
