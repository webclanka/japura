<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            ['name' => 'First Steps', 'description' => 'Complete your first quiz', 'icon' => '🎯', 'badge_color' => 'blue', 'requirement_type' => 'quizzes_completed', 'requirement_value' => 1],
            ['name' => 'Quiz Master', 'description' => 'Complete 10 quizzes', 'icon' => '🏆', 'badge_color' => 'purple', 'requirement_type' => 'quizzes_completed', 'requirement_value' => 10],
            ['name' => 'Scholar', 'description' => 'Complete 50 quizzes', 'icon' => '📚', 'badge_color' => 'indigo', 'requirement_type' => 'quizzes_completed', 'requirement_value' => 50],
            ['name' => 'Perfect Score', 'description' => 'Get a perfect score on a quiz', 'icon' => '⭐', 'badge_color' => 'gold', 'requirement_type' => 'perfect_scores', 'requirement_value' => 1],
            ['name' => 'Perfectionist', 'description' => 'Get 5 perfect scores', 'icon' => '💎', 'badge_color' => 'emerald', 'requirement_type' => 'perfect_scores', 'requirement_value' => 5],
            ['name' => 'On Fire', 'description' => 'Maintain a 3-day streak', 'icon' => '🔥', 'badge_color' => 'orange', 'requirement_type' => 'streak_days', 'requirement_value' => 3],
            ['name' => 'Unstoppable', 'description' => 'Maintain a 7-day streak', 'icon' => '⚡', 'badge_color' => 'red', 'requirement_type' => 'streak_days', 'requirement_value' => 7],
            ['name' => 'Century', 'description' => 'Earn 100 points', 'icon' => '💯', 'badge_color' => 'green', 'requirement_type' => 'points_earned', 'requirement_value' => 100],
            ['name' => 'Thousand Club', 'description' => 'Earn 1000 points', 'icon' => '🥇', 'badge_color' => 'amber', 'requirement_type' => 'points_earned', 'requirement_value' => 1000],
            ['name' => 'Legend', 'description' => 'Earn 10000 points', 'icon' => '👑', 'badge_color' => 'yellow', 'requirement_type' => 'points_earned', 'requirement_value' => 10000],
        ];

        foreach ($achievements as $achievement) {
            Achievement::firstOrCreate(['name' => $achievement['name']], $achievement);
        }
    }
}
