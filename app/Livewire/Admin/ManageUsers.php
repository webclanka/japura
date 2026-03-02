<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ManageUsers extends Component
{
    public string $search = '';

    public function toggleAdmin(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['is_admin' => !$user->is_admin]);
    }

    public function render()
    {
        $users = User::when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('livewire.admin.manage-users', compact('users'));
    }
}
