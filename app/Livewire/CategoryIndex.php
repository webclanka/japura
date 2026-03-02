<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CategoryIndex extends Component
{
    public function render()
    {
        $user = Auth::user();
        $categories = Category::where('is_active', true)
            ->withCount('publishedQuizzes')
            ->orderBy('sort_order')
            ->get();

        $completedByCategory = [];
        foreach ($categories as $cat) {
            $completedByCategory[$cat->id] = QuizAttempt::where('user_id', $user->id)
                ->where('is_completed', true)
                ->whereHas('quiz', fn($q) => $q->where('category_id', $cat->id))
                ->count();
        }

        return view('livewire.category-index', compact('categories', 'completedByCategory'));
    }
}
