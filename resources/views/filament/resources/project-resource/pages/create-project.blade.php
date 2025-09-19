<x-filament-panels::page>
    <x-filament-panels::form wire:submit="create">
        {{ $this->form }}
        
        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <!-- Drawing Modal -->
    <div id="drawingModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg w-full max-w-6xl h-5/6 flex flex-col">
                <div class="flex items-center justify-between p-4 border-b">
                    <h3 class="text-lg font-semibold">Markup Files</h3>
                    <button onclick="closeDrawingModal()" class="text-gray-400 hover:text-gray-600">×</button>
                </div>
                <div class="flex-1 flex">
                    <div class="w-64 bg-gray-50 p-4 border-r">
                        <h4 class="font-medium mb-4">Drawing Tools</h4>
 
</x-filament-panels::page>