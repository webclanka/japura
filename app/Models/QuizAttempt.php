<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'total_points_earned',
        'correct_answers',
        'wrong_answers',
        'time_taken_seconds',
        'started_at',
        'completed_at',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function responses()
    {
        return $this->hasMany(QuestionResponse::class);
    }

    public function getPercentageAttribute(): int
    {
        $total = $this->correct_answers + $this->wrong_answers;
        if ($total === 0) return 0;
        return (int) round(($this->correct_answers / $total) * 100);
    }

    public function getStarRatingAttribute(): int
    {
        $pct = $this->percentage;
        if ($pct >= 90) return 5;
        if ($pct >= 75) return 4;
        if ($pct >= 60) return 3;
        if ($pct >= 40) return 2;
        return 1;
    }
}
