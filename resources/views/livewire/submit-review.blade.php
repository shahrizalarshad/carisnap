<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-2 text-center text-brand-600">Beri Ulasan Anda</h2>
                <p class="text-center text-gray-500 mb-8">Bagaimanakah pengalaman anda berurusan dengan {{ $bookingRequest->profile->business_name }}?</p>

                @if ($alreadySubmitted)
                    <div class="text-center p-6 bg-green-50 text-green-700 rounded-lg border border-green-200 w-full font-medium flex flex-col items-center gap-3">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @if(session()->has('message'))
                            <p class="text-lg">{{ session('message') }}</p>
                        @else
                            <p class="text-lg">Anda telah pun menghantar ulasan untuk tempahan ini.</p>
                            <p class="text-sm font-normal text-green-600">Terima kasih kerana menyokong komuniti CariSnap!</p>
                        @endif
                    </div>
                @else
                    <form wire:submit="submit" class="space-y-6">
                        
                        <!-- Rating -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Penilaian (Bintang)</label>
                            <div class="flex items-center gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" wire:click="$set('rating', {{ $i }})" class="focus:outline-none transition-transform hover:scale-110">
                                        <svg class="w-10 h-10 {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            @error('rating') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Comment -->
                        <div>
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Komen & Maklum Balas (Pilihan)</label>
                            <textarea wire:model="comment" id="comment" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm" placeholder="Ceritakan pengalaman anda..."></textarea>
                            @error('comment') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-colors">
                                Hantar Ulasan
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
