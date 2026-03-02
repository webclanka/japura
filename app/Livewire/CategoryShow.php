<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CategoryShow extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $category = Category::where('slug', $this->slug)->firstOrFail();
        $user = Auth::user();

        $quizzes = $category->publishedQuizzes()->with('questions')->get();

        $bestScores = [];
        foreach ($quizzes as $quiz) {
            $best = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->where('is_completed', true)
                ->orderByDesc('score')
                ->first();
            $bestScores[$quiz->id] = $best;
        }

        return view('livewire.category-show', compact('category', 'quizzes', 'bestScores'));
    }
}
