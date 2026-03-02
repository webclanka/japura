<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'question_text', 'explanation', 'sort_order'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class)->orderBy('sort_order');
    }

    public function correctOption()
    {
        return $this->hasOne(Option::class)->where('is_correct', true);
    }

    public function responses()
    {
        return $this->hasMany(QuestionResponse::class);
    }
}
