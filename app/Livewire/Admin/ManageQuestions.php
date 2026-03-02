<?php

namespace App\Livewire\Admin;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ManageQuestions extends Component
{
    public int $quizId;
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $question_text = '';
    public string $explanation = '';
    public int $sort_order = 0;
    public array $options = [
        ['option_text' => '', 'is_correct' => false, 'sort_order' => 0],
        ['option_text' => '', 'is_correct' => false, 'sort_order' => 1],
        ['option_text' => '', 'is_correct' => false, 'sort_order' => 2],
        ['option_text' => '', 'is_correct' => false, 'sort_order' => 3],
    ];

    public function mount(Quiz $quiz): void
    {
        $this->quizId = $quiz->id;
    }

    public function openCreate(): void
    {
        $this->reset('editingId', 'question_text', 'explanation', 'sort_order');
        $this->options = [
            ['option_text' => '', 'is_correct' => false, 'sort_order' => 0],
            ['option_text' => '', 'is_correct' => false, 'sort_order' => 1],
            ['option_text' => '', 'is_correct' => false, 'sort_order' => 2],
            ['option_text' => '', 'is_correct' => false, 'sort_order' => 3],
        ];
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $question = Question::with('options')->findOrFail($id);
        $this->editingId = $id;
        $this->question_text = $question->question_text;
        $this->explanation = $question->explanation ?? '';
        $this->sort_order = $question->sort_order;
        $this->options = $question->options->map(fn($o) => [
            'id' => $o->id,
            'option_text' => $o->option_text,
            'is_correct' => $o->is_correct,
            'sort_order' => $o->sort_order,
        ])->toArray();
        $this->showForm = true;
    }

    public function setCorrect(int $index): void
    {
        foreach ($this->options as $i => $option) {
            $this->options[$i]['is_correct'] = ($i === $index);
        }
    }

    public function save(): void
    {
        $this->validate([
            'question_text' => 'required|string',
            'options.*.option_text' => 'required|string',
        ]);

        $questionData = [
            'quiz_id' => $this->quizId,
            'question_text' => $this->question_text,
            'explanation' => $this->explanation,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingId) {
            $question = Question::findOrFail($this->editingId);
            $question->update($questionData);
            $question->options()->delete();
        } else {
            $question = Question::create($questionData);
        }

        foreach ($this->options as $opt) {
            $question->options()->create([
                'option_text' => $opt['option_text'],
                'is_correct' => $opt['is_correct'] ?? false,
                'sort_order' => $opt['sort_order'],
            ]);
        }

        // Update total_questions count
        $quiz = Quiz::find($this->quizId);
        $quiz->update(['total_questions' => $quiz->questions()->count()]);

        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Question::findOrFail($id)->delete();
        $quiz = Quiz::find($this->quizId);
        $quiz->update(['total_questions' => $quiz->questions()->count()]);
    }

    public function render()
    {
        $quiz = Quiz::with('questions.options')->findOrFail($this->quizId);
        return view('livewire.admin.manage-questions', compact('quiz'));
    }
}
