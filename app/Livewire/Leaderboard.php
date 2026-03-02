<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Leaderboard extends Component
{
    public string $activeTab = 'global';
    public ?int $categoryId = null;

    public function render()
    {
        $users = collect();
        if ($this->activeTab === 'global') {
            $users = User::orderByDesc('total_points')->limit(50)->get();
        } elseif ($this->activeTab === 'weekly') {
            $users = User::withSum(['quizAttempts' => function ($q) {
                $q->where('is_completed', true)
                  ->where('completed_at', '>=', now()->subDays(7));
            }], 'total_points_earned')
            ->orderByDesc('quiz_attempts_sum_total_points_earned')
            ->limit(50)
            ->get();
        } elseif ($this->activeTab === 'category' && $this->categoryId) {
            $users = User::withSum(['quizAttempts' => function ($q) {
                $q->where('is_completed', true)
                  ->whereHas('quiz', fn($q2) => $q2->where('category_id', $this->categoryId));
            }], 'total_points_earned')
            ->orderByDesc('quiz_attempts_sum_total_points_earned')
            ->limit(50)
            ->get();
        }

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        $currentUser = Auth::user();

        return view('livewire.leaderboard', compact('users', 'categories', 'currentUser'));
    }
}
