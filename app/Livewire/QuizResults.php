<?php

namespace App\Livewire;

use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class QuizResults extends Component
{
    public string $slug;
    public int $attemptId;

    public function mount(string $slug, QuizAttempt $attempt): void
    {
        $this->slug = $slug;
        $this->attemptId = $attempt->id;

        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }
    }

    public function render()
    {
        $attempt = QuizAttempt::with(['quiz', 'responses.question.options', 'responses.option'])->find($this->attemptId);
        return view('livewire.quiz-results', compact('attempt'));
    }
}
