<div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('filament.fields.ticket_id') }}</h3>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">#{{ $record->id }}</p>
        </div>
        
        <div>
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('filament.fields.status') }}</h3>
            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                @if($record->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                @elseif($record->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                @elseif($record->status === 'resolved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                @endif">
                {{ __('filament.options.' . $record->status) }}
            </span>
        </div>
        
        <div>
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('filament.fields.customer_name') }}</h3>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->full_name }}</p>
        </div>
        
        <div>
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('filament.fields.email') }}</h3>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->email }}</p>
        </div>
        
        @if($record->user)
        <div>
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('filament.fields.user') }}</h3>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->user->full_name ?? 'N/A' }}</p>
        </div>
        @endif
        
        <div>
            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('filament.fields.created_at') }}</h3>
            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $record->created_at->format('M d, Y H:i') }}</p>
        </div>
    </div>
    
    <div>
        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('filament.fields.message') }}</h3>
        <div class="mt-1 p-3 bg-gray-50 dark:bg-gray-800 rounded-md">
            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $record->message }}</p>
        </div>
    </div>
</div>