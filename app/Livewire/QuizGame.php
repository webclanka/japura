<?php

namespace App\Livewire;

use App\Models\Option;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuestionResponse;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class QuizGame extends Component
{
    public string $slug;
    public string $state = 'start'; // start, playing, feedback, finished
    public ?int $attemptId = null;
    public int $currentIndex = 0;
    public ?int $selectedOptionId = null;
    public bool $isCorrect = false;
    public int $score = 0;
    public int $totalPoints = 0;
    public int $correctAnswers = 0;
    public int $wrongAnswers = 0;
    public array $questionIds = [];
    public int $startedAt = 0;
    public int $questionStartedAt = 0;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function startQuiz(): void
    {
        $quiz = Quiz::where('slug', $this->slug)->where('is_published', true)->firstOrFail();
        $user = Auth::user();

        $attempt = QuizAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'started_at' => now(),
        ]);

        $this->attemptId = $attempt->id;
        $this->questionIds = $quiz->questions()->pluck('id')->toArray();
        $this->currentIndex = 0;
        $this->state = 'playing';
        $this->startedAt = time();
        $this->questionStartedAt = time();
    }

    public function selectOption(int $optionId): void
    {
        if ($this->state !== 'playing') {
            return;
        }

        $this->selectedOptionId = $optionId;
        $option = Option::find($optionId);
        $this->isCorrect = $option && $option->is_correct;

        $quiz = Quiz::where('slug', $this->slug)->first();
        $timeTaken = time() - $this->questionStartedAt;

        $points = 0;
        if ($this->isCorrect) {
            $service = new GamificationService();
            $points = $service->calculatePoints(
                $quiz->points_per_correct,
                $timeTaken,
                $quiz->time_limit_per_question,
                $quiz->bonus_points_for_speed
            );
            $this->correctAnswers++;
            $this->totalPoints += $points;
            $this->score++;
        } else {
            $this->wrongAnswers++;
        }

        $questionId = $this->questionIds[$this->currentIndex];
        QuestionResponse::updateOrCreate(
            [
                'quiz_attempt_id' => $this->attemptId,
                'question_id' => $questionId,
            ],
            [
                'option_id' => $optionId,
                'is_correct' => $this->isCorrect,
                'time_taken_seconds' => $timeTaken,
                'points_earned' => $points,
            ]
        );

        $this->state = 'feedback';
    }

    #[On('time-up')]
    public function timeUp(): void
    {
        if ($this->state !== 'playing') {
            return;
        }
        $this->selectedOptionId = null;
        $this->isCorrect = false;
        $this->wrongAnswers++;

        $questionId = $this->questionIds[$this->currentIndex];
        QuestionResponse::updateOrCreate(
            [
                'quiz_attempt_id' => $this->attemptId,
                'question_id' => $questionId,
            ],
            [
                'option_id' => null,
                'is_correct' => false,
                'time_taken_seconds' => Quiz::where('slug', $this->slug)->first()->time_limit_per_question,
                'points_earned' => 0,
            ]
        );

        $this->state = 'feedback';
    }

    public function nextQuestion(): void
    {
        if ($this->currentIndex + 1 >= count($this->questionIds)) {
            $this->finishQuiz();
            return;
        }

        $this->currentIndex++;
        $this->selectedOptionId = null;
        $this->state = 'playing';
        $this->questionStartedAt = time();
    }

    private function finishQuiz(): void
    {
        $attempt = QuizAttempt::find($this->attemptId);
        $attempt->update([
            'score' => $this->correctAnswers > 0 ? (int) round(($this->correctAnswers / count($this->questionIds)) * 100) : 0,
            'total_points_earned' => $this->totalPoints,
            'correct_answers' => $this->correctAnswers,
            'wrong_answers' => $this->wrongAnswers,
            'time_taken_seconds' => time() - $this->startedAt,
            'completed_at' => now(),
            'is_completed' => true,
        ]);

        $service = new GamificationService();
        $service->completeQuiz($attempt);

        $this->state = 'finished';
    }

    public function render()
    {
        $quiz = Quiz::where('slug', $this->slug)->with('questions.options')->firstOrFail();
        $currentQuestion = null;
        $correctOptionId = null;

        if ($this->state === 'playing' || $this->state === 'feedback') {
            if (isset($this->questionIds[$this->currentIndex])) {
                $currentQuestion = $quiz->questions->firstWhere('id', $this->questionIds[$this->currentIndex]);
                if ($currentQuestion) {
                    $correct = $currentQuestion->options->firstWhere('is_correct', true);
                    $correctOptionId = $correct?->id;
                }
            }
        }

        return view('livewire.quiz-game', [
            'quiz' => $quiz,
            'currentQuestion' => $currentQuestion,
            'correctOptionId' => $correctOptionId,
            'totalQuestions' => count($this->questionIds),
        ]);
    }
}
