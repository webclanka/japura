<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $recentAttempts = QuizAttempt::where('user_id', $user->id)
            ->with('quiz')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $incompleteAttempts = QuizAttempt::where('user_id', $user->id)
            ->where('is_completed', false)
            ->with('quiz')
            ->orderByDesc('started_at')
            ->limit(3)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('publishedQuizzes')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $completedCount = QuizAttempt::where('user_id', $user->id)
            ->where('is_completed', true)
            ->count();

        return view('livewire.dashboard', compact('recentAttempts', 'incompleteAttempts', 'categories', 'completedCount'));
    }
}
