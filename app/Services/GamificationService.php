<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\QuizAttempt;
use App\Models\User;
use App\Models\UserAchievement;
use Carbon\Carbon;

class GamificationService
{
    public function calculatePoints(int $basePoints, int $timeTaken, int $timeLimit, bool $speedBonusEnabled): int
    {
        $points = $basePoints;
        if ($speedBonusEnabled && $timeTaken < ($timeLimit * 0.5)) {
            $points += (int) ($basePoints * 0.5);
        }
        return $points;
    }

    public function updateStreak(User $user): void
    {
        $lastActive = $user->last_active_at;
        $today = Carbon::today();

        if ($lastActive === null) {
            $user->current_streak = 1;
        } else {
            $lastActiveDay = Carbon::parse($lastActive)->startOfDay();
            $diffDays = $lastActiveDay->diffInDays($today);

            if ($diffDays === 1) {
                $user->current_streak += 1;
            } elseif ($diffDays > 1) {
                $user->current_streak = 1;
            }
            // if same day, don't change streak
        }

        if ($user->current_streak > $user->longest_streak) {
            $user->longest_streak = $user->current_streak;
        }
    }

    public function updateLevel(User $user): void
    {
        $user->level = (int) floor($user->total_points / 100) + 1;
    }

    public function awardPoints(User $user, int $points): void
    {
        $user->total_points += $points;
        $this->updateLevel($user);
    }

    public function checkAchievements(User $user): array
    {
        $earned = $user->achievements->pluck('id')->toArray();
        $achievements = Achievement::all();
        $newlyEarned = [];

        foreach ($achievements as $achievement) {
            if (in_array($achievement->id, $earned)) {
                continue;
            }

            $qualified = false;
            switch ($achievement->requirement_type) {
                case 'quizzes_completed':
                    $qualified = $user->completedAttempts()->count() >= $achievement->requirement_value;
                    break;
                case 'perfect_scores':
                    $qualified = $user->completedAttempts()
                        ->whereColumn('correct_answers', 'total_points_earned') // proxy: use score
                        ->where('score', 100)
                        ->count() >= $achievement->requirement_value;
                    break;
                case 'streak_days':
                    $qualified = $user->current_streak >= $achievement->requirement_value;
                    break;
                case 'points_earned':
                    $qualified = $user->total_points >= $achievement->requirement_value;
                    break;
            }

            if ($qualified) {
                UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->id,
                    'earned_at' => now(),
                ]);
                $newlyEarned[] = $achievement;
            }
        }

        return $newlyEarned;
    }

    public function completeQuiz(QuizAttempt $attempt): array
    {
        $user = $attempt->user;
        $this->awardPoints($user, $attempt->total_points_earned);
        $this->updateStreak($user);
        $user->last_active_at = now();
        $user->save();
        $user->refresh();
        $user->load('achievements');
        return $this->checkAchievements($user);
    }
}
