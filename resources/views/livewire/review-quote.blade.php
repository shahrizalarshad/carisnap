<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6 text-center text-brand-600">Semak Sebut Harga</h2>

                @if (session()->has('message'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="mb-8 border-b pb-6">
                    <h3 class="text-lg font-semibold mb-4">Butiran Permohonan Tempahan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 block">Jurugambar:</span>
                            <span class="font-medium">{{ $quote->bookingRequest->profile->business_name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Jenis Acara:</span>
                            <span class="font-medium capitalize">{{ $quote->bookingRequest->event_type }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Tarikh Acara:</span>
                            <span class="font-medium">{{ \Carbon\Carbon::parse($quote->bookingRequest->event_date)->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block">Lokasi:</span>
                            <span class="font-medium">{{ $quote->bookingRequest->location }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Sebut Harga (Quote)</h3>
                    <div class="bg-brand-50 rounded-lg p-6 border border-brand-100">
                        <div class="flex items-end justify-between mb-4">
                            <div>
                                <span class="text-brand-600 font-semibold block uppercase text-xs tracking-wider">Jumlah Sebut Harga</span>
                                <span class="text-3xl font-bold text-gray-900">RM {{ number_format($quote->amount) }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-gray-500 text-sm block">Sah sehingga:</span>
                                <span class="font-medium {{ now()->toDateString() > $quote->valid_until->toDateString() ? 'text-red-500' : 'text-gray-900' }}">
                                    {{ \Carbon\Carbon::parse($quote->valid_until)->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>

                        @if($quote->message)
                            <div class="mt-4 pt-4 border-t border-brand-200">
                                <span class="text-gray-500 block mb-1 text-sm">Mesej dari jurugambar:</span>
                                <p class="text-gray-700 whitespace-pre-wrap italic">"{{ $quote->message }}"</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
                    @if($quote->status === \App\Enums\QuoteStatus::Sent)
                        @if(now()->toDateString() <= $quote->valid_until->toDateString())
                            <button wire:click="decline" wire:confirm="Adakah anda pasti untuk MENOLAK sebut harga ini?" class="px-6 py-3 border border-red-500 text-red-500 font-medium rounded-md hover:bg-red-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-center">
                                Tolak Sebut Harga
                            </button>
                            <button wire:click="accept" wire:confirm="Adakah anda pasti untuk MENERIMA sebut harga ini? Anda akan dihubungi oleh jurugambar selepas ini." class="px-6 py-3 bg-brand-600 text-white font-medium rounded-md hover:bg-brand-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 text-center">
                                Terima Sebut Harga
                            </button>
                        @else
                            <div class="text-center p-4 bg-red-50 text-red-600 rounded-md border border-red-200 w-full font-medium">
                                Sebut harga ini telah tamat tempoh pada {{ \Carbon\Carbon::parse($quote->valid_until)->format('d/m/Y') }}.
                            </div>
                        @endif
                    @elseif($quote->status === \App\Enums\QuoteStatus::Accepted)
                        <div class="w-full flex flex-col gap-3">
                            <div class="text-center p-4 bg-green-50 text-green-700 rounded-md border border-green-200 font-medium flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Anda telah MENERIMA sebut harga ini.
                            </div>
                            <a href="{{ $quote->bookingRequest->profile->whatsapp_url }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-[#25D366] text-white font-medium rounded-md hover:bg-[#128C7E] transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#25D366]">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                Bincang Lanjut di WhatsApp
                            </a>
                        </div>
                    @elseif($quote->status === \App\Enums\QuoteStatus::Declined)
                        <div class="text-center p-4 bg-gray-50 text-gray-600 rounded-md border border-gray-200 w-full font-medium flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Anda telah MENOLAK sebut harga ini.
                        </div>
                    @elseif($quote->status === \App\Enums\QuoteStatus::Expired)
                        <div class="text-center p-4 bg-red-50 text-red-600 rounded-md border border-red-200 w-full font-medium">
                            Sebut harga ini telah tamat tempoh.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
