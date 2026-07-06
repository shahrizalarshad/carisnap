<div x-data="{ open: false }" 
     x-on:open-booking-modal.window="open = true"
     x-on:keydown.escape.window="open = false">
    
    <!-- Overlay -->
    <div x-show="open" 
         x-transition.opacity 
         class="fixed inset-0 bg-black/50 z-50 backdrop-blur-sm"
         x-on:click="open = false"></div>

    <!-- Modal/Bottom Sheet -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
         class="fixed inset-x-0 bottom-0 sm:inset-0 sm:flex sm:items-center sm:justify-center z-50 pointer-events-none">
        
        <div class="bg-white rounded-t-2xl sm:rounded-2xl shadow-xl w-full sm:max-w-md max-h-[90vh] overflow-y-auto pointer-events-auto flex flex-col">
            <!-- Drag Handle (Mobile) -->
            <div class="sm:hidden flex justify-center pt-3 pb-1" x-on:click="open = false">
                <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
            </div>

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                <h2 class="text-xl font-heading font-bold text-gray-900">Booking Request</h2>
                <button type="button" x-on:click="open = false" class="text-gray-400 hover:text-gray-600 p-2 -mr-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Content -->
            <div class="p-6">
                @if($success)
                    <div class="text-center py-8">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-lg font-heading font-bold text-gray-900 mb-2">Permintaan Berjaya Dihantar!</h3>
                        <p class="text-gray-600 mb-6">Permintaan anda telah dihantar kepada jurugambar. Mereka akan membalas dalam masa 24 jam.</p>
                        <x-ui.button variant="primary" class="w-full" x-on:click="open = false">Tutup</x-ui.button>
                    </div>
                @else
                    <form wire:submit="submit" class="space-y-4">
                        
                        @if($showAvailabilityWarning)
                            <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-lg p-4 text-sm flex gap-3 items-start">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <div>
                                    <strong class="font-bold">Perhatian:</strong>
                                    Jurugambar belum menetapkan tarikh ini sebagai kekosongan. Anda masih boleh menghantar request, tetapi mereka mungkin sibuk.
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tarikh Majlis</label>
                            <input type="date" wire:model.live.debounce.500ms="event_date" min="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                            @error('event_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Majlis</label>
                            <input type="text" wire:model="location" placeholder="Cth: Dewan Seri Kenangan, KL" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                            @error('location') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bajet</label>
                            <select wire:model="budget_range" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                <option value="">Pilih bajet anda</option>
                                <option value="500-1000">RM 500 - RM 1,000</option>
                                <option value="1000-2000">RM 1,000 - RM 2,000</option>
                                <option value="2000-3000">RM 2,000 - RM 3,000</option>
                                <option value="3000-5000">RM 3,000 - RM 5,000</option>
                                <option value="5000+">RM 5,000+</option>
                            </select>
                            @error('budget_range') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mesej (Pilihan)</label>
                            <textarea wire:model="message" rows="3" placeholder="Ceritakan sikit tentang majlis anda..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
                            @error('message') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        @guest
                            <div class="border-t border-gray-100 pt-4 mt-2 space-y-4">
                                <h3 class="font-medium text-gray-900">Maklumat Anda</h3>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                    <input type="text" wire:model="guest_name" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                    @error('guest_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telefon (WhatsApp)</label>
                                    <input type="tel" wire:model="guest_phone" placeholder="0123456789" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                    @error('guest_phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mel (Pilihan)</label>
                                    <input type="email" wire:model="guest_email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
                                    @error('guest_email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endguest

                        <div class="pt-4">
                            <x-ui.button type="submit" variant="primary" class="w-full" size="lg" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="submit">Hantar Permintaan</span>
                                <span wire:loading wire:target="submit">Menghantar...</span>
                            </x-ui.button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
