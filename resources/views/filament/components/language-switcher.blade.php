<x-filament::dropdown>
    <x-slot name="trigger">
        <x-filament::button
            color="gray"
            icon="heroicon-o-language"
            size="sm"
        >
            {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
        </x-filament::button>
    </x-slot>

    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item
            tag="a"
            :href="request()->fullUrlWithQuery(['lang' => 'en'])"
            icon="heroicon-o-flag"
        >
            English
        </x-filament::dropdown.list.item>
        
        <x-filament::dropdown.list.item
            tag="a"
            :href="request()->fullUrlWithQuery(['lang' => 'ar'])"
            icon="heroicon-o-flag"
        >
            العربية
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown>