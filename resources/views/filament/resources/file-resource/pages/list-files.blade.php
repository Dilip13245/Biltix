<x-filament-panels::page>
    @if (!$selectedProjectId)
        {{-- Project Folders View --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($projects as $project)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow cursor-pointer"
                    onclick="window.location.href='{{ static::getUrl(['project_id' => $project->id]) }}'">
                    <div class="p-2 text-center">
                        {{-- Folder Icon --}}
                        <div class="mx-auto w-9 h-9 mb-1 flex items-center justify-center">
                            <svg class="w-9 h-9 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                            </svg>
                        </div>

                        {{-- Project Name --}}
                        <h3 class="text-xs font-medium text-gray-900 dark:text-white mb-1 truncate">
                            {{ $project->project_title }}
                        </h3>

                        {{-- File Count --}}
                        <div class="text-xs text-gray-500">
                            {{ $project->files_count }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9 6 9-6" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('filament.messages.no_projects') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('filament.messages.create_project_first') }}</p>
                </div>
            @endforelse
        </div>
    @else
        {{-- Project Files View --}}
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <a href="{{ static::getUrl() }}" class="hover:text-gray-700 dark:hover:text-gray-200">
                    {{ __('filament.resources.files') }}
                </a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium text-gray-900 dark:text-white">{{ $selectedProject->project_title }}</span>
            </div>
        </div>

        {{-- File Grid View --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
            @forelse($this->getTableRecords() as $file)
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="p-2">
                        {{-- File Icon --}}
                        <div class="flex items-center justify-center w-8 h-8 mx-auto mb-2">
                            @php
                                $extension = strtolower(pathinfo($file->file_type, PATHINFO_EXTENSION));
                            @endphp

                            @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset('storage/' . $file->file_path) }}" alt="{{ $file->original_name }}"
                                    class="w-8 h-8 object-cover rounded">
                            @else
                                <div
                                    class="w-8 h-8 flex items-center justify-center rounded
                                    @if ($extension === 'pdf') bg-red-100 text-red-600
                                    @elseif(in_array($extension, ['doc', 'docx'])) bg-blue-100 text-blue-600
                                    @elseif(in_array($extension, ['xls', 'xlsx'])) bg-green-100 text-green-600
                                    @else bg-gray-100 text-gray-600 @endif">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- File Name --}}
                        <h4 class="text-xs font-medium text-gray-900 dark:text-white mb-1 truncate"
                            title="{{ $file->original_name }}">
                            {{ $file->original_name }}
                        </h4>

                        {{-- File Info --}}
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                            <div>{{ strtoupper(pathinfo($file->file_type, PATHINFO_EXTENSION)) }}</div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex space-x-1">
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank"
                                class="flex-1 inline-flex items-center justify-center px-2 py-1 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                {{ __('filament.actions.view') }}
                            </a>

                            <a href="{{ asset('storage/' . $file->file_path) }}" download="{{ $file->original_name }}"
                                class="flex-1 inline-flex items-center justify-center px-2 py-1 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                {{ __('filament.actions.download') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-6">
                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('filament.messages.no_files') }}</h3>
                </div>
            @endforelse
        </div>

        {{-- Table View Toggle --}}
        <div class="mt-8">
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    {{ __('filament.labels.detailed_view') }}</h3>
                {{ $this->table }}
            </div>
        </div>
    @endif
</x-filament-panels::page>
