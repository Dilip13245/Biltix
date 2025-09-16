<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <x-heroicon-o-information-circle class="w-8 h-8 text-blue-500" />
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Permission Matrix</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Manage role permissions using this visual matrix. Click on cells to toggle permissions, or use row/column headers for bulk operations.
                    </p>
                </div>
            </div>
        </div>

        <!-- Permission Matrix -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky left-0 bg-gray-50 dark:bg-gray-700 z-10">
                                Permission
                            </th>
                            @foreach($roles as $role)
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[120px]">
                                    <div class="space-y-1">
                                        <div class="font-semibold">{{ $role->display_name }}</div>
                                        <div class="text-xs text-gray-400">{{ $role->name }}</div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($permissions as $module => $modulePermissions)
                            <!-- Module Header -->
                            <tr class="bg-blue-50 dark:bg-blue-900/20">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-900 dark:text-blue-100 sticky left-0 bg-blue-50 dark:bg-blue-900/20 z-10">
                                    <div class="flex items-center space-x-2">
                                        <x-heroicon-o-folder class="w-4 h-4" />
                                        <span>{{ ucfirst(str_replace('_', ' ', $module)) }}</span>
                                    </div>
                                </td>
                                @foreach($roles as $role)
                                    <td class="px-3 py-4 text-center">
                                        <button 
                                            wire:click="toggleRoleModule({{ $role->id }}, '{{ $module }}')"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-800 dark:text-blue-100 dark:hover:bg-blue-700 transition-colors"
                                        >
                                            Toggle All
                                        </button>
                                    </td>
                                @endforeach
                            </tr>
                            
                            <!-- Permission Rows -->
                            @foreach($modulePermissions as $permission)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 sticky left-0 bg-white dark:bg-gray-800 z-10">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                @switch($permission->action)
                                                    @case('create')
                                                        <x-heroicon-o-plus-circle class="w-4 h-4 text-green-500" />
                                                        @break
                                                    @case('edit')
                                                    @case('update')
                                                        <x-heroicon-o-pencil class="w-4 h-4 text-yellow-500" />
                                                        @break
                                                    @case('delete')
                                                        <x-heroicon-o-trash class="w-4 h-4 text-red-500" />
                                                        @break
                                                    @case('view')
                                                        <x-heroicon-o-eye class="w-4 h-4 text-blue-500" />
                                                        @break
                                                    @default
                                                        <x-heroicon-o-key class="w-4 h-4 text-gray-500" />
                                                @endswitch
                                            </div>
                                            <div>
                                                <div class="font-medium">{{ $permission->display_name }}</div>
                                                @if($permission->description)
                                                    <div class="text-xs text-gray-500">{{ $permission->description }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($roles as $role)
                                        <td class="px-3 py-4 text-center">
                                            <button 
                                                wire:click="togglePermission({{ $role->id }}, {{ $permission->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full transition-all duration-200 {{ ($matrix[$role->id][$permission->id] ?? false) ? 'bg-green-100 text-green-600 hover:bg-green-200 dark:bg-green-800 dark:text-green-100' : 'bg-gray-100 text-gray-400 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-500' }}"
                                            >
                                                @if($matrix[$role->id][$permission->id] ?? false)
                                                    <x-heroicon-o-check class="w-5 h-5" />
                                                @else
                                                    <x-heroicon-o-x-mark class="w-5 h-5" />
                                                @endif
                                            </button>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend -->
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Legend</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-plus-circle class="w-4 h-4 text-green-500" />
                    <span class="text-gray-600 dark:text-gray-300">Create</span>
                </div>
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-eye class="w-4 h-4 text-blue-500" />
                    <span class="text-gray-600 dark:text-gray-300">View</span>
                </div>
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-pencil class="w-4 h-4 text-yellow-500" />
                    <span class="text-gray-600 dark:text-gray-300">Edit/Update</span>
                </div>
                <div class="flex items-center space-x-2">
                    <x-heroicon-o-trash class="w-4 h-4 text-red-500" />
                    <span class="text-gray-600 dark:text-gray-300">Delete</span>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>