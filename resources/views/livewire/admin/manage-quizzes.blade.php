<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">📝 Manage Quizzes</h1>
        <button wire:click="openCreate" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">+ New Quiz</button>
    </div>

    @if($showForm)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6 border border-indigo-200">
        <h2 class="font-semibold text-gray-800 dark:text-white mb-4">{{ $editingId ? 'Edit Quiz' : 'New Quiz' }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                <input wire:model.live="title" type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                <input wire:model="slug" type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                <select wire:model="category_id" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Difficulty</label>
                <select wire:model="difficulty" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time Per Question (sec)</label>
                <input wire:model="time_limit_per_question" type="number" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Points Per Correct</label>
                <input wire:model="points_per_correct" type="number" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea wire:model="description" rows="2" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            <div class="flex items-center space-x-2">
                <input wire:model="bonus_points_for_speed" type="checkbox" id="bonus_speed" class="rounded border-gray-300">
                <label for="bonus_speed" class="text-sm text-gray-700 dark:text-gray-300">Speed Bonus</label>
            </div>
            <div class="flex items-center space-x-2">
                <input wire:model="is_published" type="checkbox" id="is_published" class="rounded border-gray-300">
                <label for="is_published" class="text-sm text-gray-700 dark:text-gray-300">Published</label>
            </div>
        </div>
        <div class="flex space-x-3 mt-4">
            <button wire:click="save" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Save</button>
            <button wire:click="$set('showForm', false)" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm">Cancel</button>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quiz</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Difficulty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Questions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($quizzes as $quiz)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $quiz->title }}</div>
                        <div class="text-xs text-gray-400">{{ $quiz->slug }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $quiz->category->name ?? '-' }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $quiz->difficulty === 'easy' ? 'bg-green-100 text-green-700' : ($quiz->difficulty === 'hard' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($quiz->difficulty) }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $quiz->total_questions }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $quiz->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $quiz->is_published ? 'Published' : 'Draft' }}</span></td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.questions', $quiz->id) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Questions</a>
                        <button wire:click="togglePublish({{ $quiz->id }})" class="text-amber-500 hover:text-amber-700 text-sm font-medium">{{ $quiz->is_published ? 'Unpublish' : 'Publish' }}</button>
                        <button wire:click="edit({{ $quiz->id }})" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</button>
                        <button wire:click="delete({{ $quiz->id }})" wire:confirm="Delete this quiz?" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No quizzes yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
