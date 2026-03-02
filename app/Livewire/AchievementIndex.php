<?php

namespace App\Livewire;

use App\Models\Achievement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class AchievementIndex extends Component
{
    public function render()
    {
        $user = Auth::user()->load('achievements');
        $earnedIds = $user->achievements->pluck('id')->toArray();
        $achievements = Achievement::all();

        return view('livewire.achievement-index', compact('achievements', 'earnedIds', 'user'));
    }
}
