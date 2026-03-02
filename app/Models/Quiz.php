<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'difficulty',
        'time_limit_per_question',
        'points_per_correct',
        'bonus_points_for_speed',
        'is_published',
        'total_questions',
    ];

    protected $casts = [
        'bonus_points_for_speed' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('sort_order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function getDifficultyColorAttribute(): string
    {
        return match($this->difficulty) {
            'easy' => 'green',
            'hard' => 'red',
            default => 'yellow',
        };
    }

    public function getEstimatedTimeAttribute(): int
    {
        return $this->total_questions * $this->time_limit_per_question;
    }

    public function getMaxPointsAttribute(): int
    {
        return $this->total_questions * $this->points_per_correct;
    }
}
