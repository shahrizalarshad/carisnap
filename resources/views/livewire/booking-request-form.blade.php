<x-ui.bottom-sheet id="booking-modal" title="Hantar Request Tempahan">
<div>
    @if ($isSubmitted)
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-xl font-heading font-semibold text-gray-900 mb-2">Permintaan Berjaya Dihantar!</h3>
            <p class="text-gray-500 mb-6">Kami telah memaklumkan kepada {{ $profile->business_name }}. Anda akan dihubungi dalam masa 24 jam untuk pengesahan dan sebut harga.</p>
            <x-ui.button variant="primary" class="w-full" @click="open = false">Tutup</x-ui.button>
        </div>
    @else
        <form wire:submit="submit" class="space-y-6 pb-6">
            @if ($selectedPackage)
                <div class="bg-brand-50 p-4 rounded-xl border border-brand-100 mb-6">
                    <p class="text-sm text-brand-600 font-medium">Pakej Pilihan</p>
                    <p class="text-lg font-semibold text-brand-900">{{ $selectedPackage->name }} (Dari RM{{ number_format($selectedPackage->price_from) }})</p>
                </div>
            @endif

            <!-- Guest Fields -->
            @guest
                <div class="space-y-4">
                    <h4 class="text-sm font-medium text-gray-900 border-b pb-2">Maklumat Anda</h4>
                    <div>
                        <label for="guest_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Penuh <span class="text-red-500">*</span></label>
                        <input type="text" id="guest_name" wire:model="guest_name" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm" placeholder="Ali bin Abu">
                        @error('guest_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="guest_phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telefon (WhatsApp) <span class="text-red-500">*</span></label>
                        <input type="text" id="guest_phone" wire:model="guest_phone" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm" placeholder="0123456789">
                        @error('guest_phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <label for="guest_email" class="block text-sm font-medium text-gray-700 mb-1">Emel (Pilihan)</label>
                        <input type="email" id="guest_email" wire:model="guest_email" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm" placeholder="ali@gmail.com">
                        @error('guest_email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endguest

            <div class="space-y-4">
                <h4 class="text-sm font-medium text-gray-900 border-b pb-2 pt-2">Maklumat Majlis</h4>
                
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Tarikh Majlis <span class="text-red-500">*</span></label>
                    <input type="date" id="event_date" wire:model.live="event_date" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    @error('event_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    
                    @if($this->is_date_unavailable)
                        <div class="mt-2 p-3 bg-red-50 text-red-600 text-sm rounded-lg flex gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span>Harap maaf, jurugambar ini mungkin tidak berkelapangan pada tarikh ini berdasarkan kalendar beliau. Anda masih boleh menghantar request.</span>
                        </div>
                    @endif
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Lokasi (Kawasan) <span class="text-red-500">*</span></label>
                    <select id="location" wire:model="location" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm">
                        <option value="">Pilih lokasi...</option>
                        @foreach($profile->coverage_areas as $area)
                            <option value="{{ $area }}">{{ $area }}</option>
                        @endforeach
                    </select>
                    @error('location') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="budget_from" class="block text-sm font-medium text-gray-700 mb-1">Bajet Dari (RM) <span class="text-red-500">*</span></label>
                        <input type="number" id="budget_from" wire:model="budget_from" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm" min="0" step="100">
                        @error('budget_from') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="budget_to" class="block text-sm font-medium text-gray-700 mb-1">Bajet Hingga (RM) <span class="text-red-500">*</span></label>
                        <input type="number" id="budget_to" wire:model="budget_to" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm" min="0" step="100">
                        @error('budget_to') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Mesej Tambahan (Pilihan)</label>
                    <textarea id="message" wire:model="message" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm" placeholder="Contoh: Majlis nikah pagi, sanding tengah hari..."></textarea>
                    @error('message') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex gap-3">
                <x-ui.button type="button" variant="ghost" class="w-full" @click="open = false">Batal</x-ui.button>
                <x-ui.button type="submit" variant="primary" class="w-full">Hantar Request</x-ui.button>
            </div>
        </form>
    @endif
</div>
</x-ui.bottom-sheet>
