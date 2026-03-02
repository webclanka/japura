<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'icon', 'badge_color', 'requirement_type', 'requirement_value'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_achievements')->withPivot('earned_at');
    }
}
