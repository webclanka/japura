<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class ManageCategories extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $icon = '';
    public string $color = '';
    public bool $is_active = true;
    public int $sort_order = 0;

    public function openCreate(): void
    {
        $this->reset('editingId', 'name', 'slug', 'description', 'icon', 'color', 'sort_order');
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $cat = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name = $cat->name;
        $this->slug = $cat->slug;
        $this->description = $cat->description ?? '';
        $this->icon = $cat->icon ?? '';
        $this->color = $cat->color ?? '';
        $this->is_active = $cat->is_active;
        $this->sort_order = $cat->sort_order;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'description' => $this->description,
            'icon' => $this->icon,
            'color' => $this->color,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($data);
        } else {
            Category::create($data);
        }

        $this->showForm = false;
        $this->reset('editingId', 'name', 'slug', 'description', 'icon', 'color', 'sort_order');
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();
    }

    public function updatedName(): void
    {
        if (!$this->editingId) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function render()
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('livewire.admin.manage-categories', compact('categories'));
    }
}
