<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">👥 Manage Users</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 mb-4">
        <input wire:model.live="search" type="text" placeholder="Search users by name or email..." class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">University</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Points</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Admin</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                        <div class="text-xs text-gray-400">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->university }}</td>
                    <td class="px-6 py-4 text-sm font-bold text-indigo-600">{{ number_format($user->total_points) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $user->level }}</td>
                    <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $user->is_admin ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-500' }}">{{ $user->is_admin ? 'Admin' : 'User' }}</span></td>
                    <td class="px-6 py-4 text-right">
                        <button wire:click="toggleAdmin({{ $user->id }})" class="text-sm font-medium {{ $user->is_admin ? 'text-red-500 hover:text-red-700' : 'text-purple-600 hover:text-purple-800' }}">
                            {{ $user->is_admin ? 'Revoke Admin' : 'Make Admin' }}
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $users->links() }}
        </div>
    </div>
</div>
