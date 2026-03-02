<?php

use App\Livewire\AchievementIndex;
use App\Livewire\CategoryIndex;
use App\Livewire\CategoryShow;
use App\Livewire\Dashboard;
use App\Livewire\Leaderboard;
use App\Livewire\QuizGame;
use App\Livewire\QuizResults;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\ManageCategories;
use App\Livewire\Admin\ManageQuestions;
use App\Livewire\Admin\ManageQuizzes;
use App\Livewire\Admin\ManageUsers;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Student routes (auth required)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/categories', CategoryIndex::class)->name('categories.index');
    Route::get('/categories/{slug}', CategoryShow::class)->name('categories.show');
    Route::get('/quiz/{slug}', QuizGame::class)->name('quiz.play');
    Route::get('/quiz/{slug}/results/{attempt}', QuizResults::class)->name('quiz.results');
    Route::get('/leaderboard', Leaderboard::class)->name('leaderboard');
    Route::get('/achievements', AchievementIndex::class)->name('achievements');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/categories', ManageCategories::class)->name('categories');
    Route::get('/quizzes', ManageQuizzes::class)->name('quizzes');
    Route::get('/quizzes/{quiz}/questions', ManageQuestions::class)->name('questions');
    Route::get('/users', ManageUsers::class)->name('users');
});

require __DIR__.'/auth.php';
