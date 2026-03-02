<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.quizzes') }}" class="text-indigo-600 text-sm hover:underline">← Back to Quizzes</a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">📝 Questions: {{ $quiz->title }}</h1>
        </div>
        <button wire:click="openCreate" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">+ Add Question</button>
    </div>

    @if($showForm)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6 border border-indigo-200">
        <h2 class="font-semibold text-gray-800 dark:text-white mb-4">{{ $editingId ? 'Edit Question' : 'New Question' }}</h2>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Question Text *</label>
            <textarea wire:model="question_text" rows="3" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white"></textarea>
            @error('question_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Explanation (optional)</label>
            <textarea wire:model="explanation" rows="2" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white"></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Options (click radio to mark correct)</label>
            @foreach($options as $i => $option)
            <div class="flex items-center space-x-3 mb-2">
                <input type="radio" name="correct_option" wire:click="setCorrect({{ $i }})" @checked($option['is_correct']) class="text-indigo-600">
                <input wire:model="options.{{ $i }}.option_text" type="text" placeholder="Option {{ chr(65 + $i) }}" class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
                @error("options.{$i}.option_text") <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            </div>
            @endforeach
        </div>
        <div class="flex space-x-3">
            <button wire:click="save" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Save</button>
            <button wire:click="$set('showForm', false)" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm">Cancel</button>
        </div>
    </div>
    @endif

    <div class="space-y-3">
        @forelse($quiz->questions as $question)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 border border-gray-100 dark:border-gray-700">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <p class="font-medium text-gray-800 dark:text-white">{{ $loop->iteration }}. {{ $question->question_text }}</p>
                    <div class="mt-2 space-y-1">
                        @foreach($question->options as $opt)
                        <div class="text-sm px-3 py-1 rounded {{ $opt->is_correct ? 'bg-green-100 text-green-700' : 'text-gray-500' }}">
                            {{ chr(65 + $loop->index) }}. {{ $opt->option_text }}{{ $opt->is_correct ? ' ✓' : '' }}
                        </div>
                        @endforeach
                    </div>
                    @if($question->explanation)
                    <p class="mt-2 text-xs text-gray-400 italic">💡 {{ $question->explanation }}</p>
                    @endif
                </div>
                <div class="flex space-x-2 ml-4">
                    <button wire:click="edit({{ $question->id }})" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</button>
                    <button wire:click="delete({{ $question->id }})" wire:confirm="Delete this question?" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-500">No questions yet. Add your first question!</div>
        @endforelse
    </div>
</div>
