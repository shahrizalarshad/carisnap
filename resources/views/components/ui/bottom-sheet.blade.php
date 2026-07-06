@props(['id'])

<div x-data="{ open: false }" 
     @open-sheet-{{ $id }}.window="open = true" 
     @close-sheet-{{ $id }}.window="open = false" 
     @keydown.escape.window="open = false"
     class="relative z-50" 
     x-show="open" 
     style="display: none;">
     
    <!-- Overlay -->
    <div x-show="open" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
         @click="open = false"></div>

    <!-- Sheet Panel -->
    <div class="fixed inset-0 flex flex-col justify-end sm:justify-center sm:items-center pointer-events-none">
        <div x-show="open" 
             x-transition:enter="transform transition ease-out duration-300"
             x-transition:enter-start="translate-y-full sm:translate-y-4 sm:opacity-0 sm:scale-95"
             x-transition:enter-end="translate-y-0 sm:translate-y-0 sm:opacity-100 sm:scale-100"
             x-transition:leave="transform transition ease-in duration-200"
             x-transition:leave-start="translate-y-0 sm:translate-y-0 sm:opacity-100 sm:scale-100"
             x-transition:leave-end="translate-y-full sm:translate-y-4 sm:opacity-0 sm:scale-95"
             class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-2xl shadow-xl pointer-events-auto flex flex-col max-h-[90vh]">
            
            <!-- Mobile Drag Handle -->
            <div class="sm:hidden flex justify-center pt-4 pb-2" @click="open = false">
                <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
            </div>
            
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-heading font-semibold text-gray-900">{{ $title ?? 'Menu' }}</h2>
                <button @click="open = false" class="text-gray-400 hover:text-gray-500 focus:outline-none p-2 -mr-2">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="px-6 py-4 overflow-y-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
