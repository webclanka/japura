<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AdminDashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.admin-dashboard', [
            'totalUsers' => User::count(),
            'totalQuizzes' => Quiz::count(),
            'totalCategories' => Category::count(),
            'totalAttempts' => QuizAttempt::where('is_completed', true)->count(),
        ]);
    }
}
