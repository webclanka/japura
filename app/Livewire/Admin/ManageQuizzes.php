<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ManageQuizzes extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $title = '';
    public string $slug = '';
    public string $description = '';
    public int $category_id = 0;
    public string $difficulty = 'medium';
    public int $time_limit_per_question = 30;
    public int $points_per_correct = 10;
    public bool $bonus_points_for_speed = true;
    public bool $is_published = false;

    public function openCreate(): void
    {
        $this->reset('editingId', 'title', 'slug', 'description', 'category_id', 'difficulty', 'time_limit_per_question', 'points_per_correct');
        $this->difficulty = 'medium';
        $this->time_limit_per_question = 30;
        $this->points_per_correct = 10;
        $this->bonus_points_for_speed = true;
        $this->is_published = false;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $quiz = Quiz::findOrFail($id);
        $this->editingId = $id;
        $this->title = $quiz->title;
        $this->slug = $quiz->slug;
        $this->description = $quiz->description ?? '';
        $this->category_id = $quiz->category_id;
        $this->difficulty = $quiz->difficulty;
        $this->time_limit_per_question = $quiz->time_limit_per_question;
        $this->points_per_correct = $quiz->points_per_correct;
        $this->bonus_points_for_speed = $quiz->bonus_points_for_speed;
        $this->is_published = $quiz->is_published;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug ?: Str::slug($this->title),
            'description' => $this->description,
            'category_id' => $this->category_id,
            'difficulty' => $this->difficulty,
            'time_limit_per_question' => $this->time_limit_per_question,
            'points_per_correct' => $this->points_per_correct,
            'bonus_points_for_speed' => $this->bonus_points_for_speed,
            'is_published' => $this->is_published,
        ];

        if ($this->editingId) {
            Quiz::findOrFail($this->editingId)->update($data);
        } else {
            Quiz::create($data);
        }

        $this->showForm = false;
    }

    public function togglePublish(int $id): void
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->update(['is_published' => !$quiz->is_published]);
    }

    public function delete(int $id): void
    {
        Quiz::findOrFail($id)->delete();
    }

    public function updatedTitle(): void
    {
        if (!$this->editingId) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function render()
    {
        $quizzes = Quiz::with('category')->orderByDesc('created_at')->get();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('livewire.admin.manage-quizzes', compact('quizzes', 'categories'));
    }
}
