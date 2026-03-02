<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">📂 Manage Categories</h1>
        <button wire:click="openCreate" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">+ New Category</button>
    </div>

    @if($showForm)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-6 border border-indigo-200">
        <h2 class="font-semibold text-gray-800 dark:text-white mb-4">{{ $editingId ? 'Edit Category' : 'New Category' }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name *</label>
                <input wire:model.live="name" type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slug *</label>
                <input wire:model="slug" type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Icon (emoji)</label>
                <input wire:model="icon" type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Color</label>
                <input wire:model="color" type="text" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white" placeholder="#4F46E5">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <textarea wire:model="description" rows="2" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white"></textarea>
            </div>
            <div class="flex items-center space-x-2">
                <input wire:model="is_active" type="checkbox" id="is_active" class="rounded border-gray-300">
                <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Active</label>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
                <input wire:model="sort_order" type="number" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
            </div>
        </div>
        <div class="flex space-x-3 mt-4">
            <button wire:click="save" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Save</button>
            <button wire:click="$set('showForm', false)" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm">Cancel</button>
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($categories as $cat)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">{{ $cat->icon }}</span>
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $cat->name }}</div>
                                <div class="text-xs text-gray-400">{{ $cat->description }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $cat->slug }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $cat->sort_order }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button wire:click="edit({{ $cat->id }})" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</button>
                        <button wire:click="delete({{ $cat->id }})" wire:confirm="Delete this category?" class="text-red-500 hover:text-red-700 text-sm font-medium">Delete</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No categories yet. Create one!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
