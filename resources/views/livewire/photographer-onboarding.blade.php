<div class="max-w-2xl mx-auto space-y-6">
    <div class="border-b border-gray-100 pb-4">
        <x-ui.badge color="brand" class="mb-3">Langkah 2 daripada 2</x-ui.badge>
        <h1 class="text-3xl font-heading font-bold text-gray-900">Setup profil jurugambar</h1>
        <p class="mt-2 text-sm text-gray-500">
            Isi butiran asas studio anda. Profil akan disemak pasukan kami sebelum dipaparkan kepada pelanggan.
        </p>
    </div>

    <form wire:submit="submit" class="space-y-6">
        <x-ui.card class="p-6 space-y-5">
            <div>
                <label for="business_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Studio / Brand</label>
                <input
                    id="business_name"
                    type="text"
                    wire:model="business_name"
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm"
                    placeholder="Contoh: Aiman Wedding Studio"
                >
                @error('business_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio (min. 50 aksara)</label>
                <textarea
                    id="bio"
                    wire:model="bio"
                    rows="4"
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm"
                    placeholder="Ceritakan gaya fotografi anda, pengalaman, dan apa yang buat studio anda istimewa..."
                ></textarea>
                @error('bio') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="location_area" class="block text-sm font-medium text-gray-700 mb-1">Lokasi Utama</label>
                <select
                    id="location_area"
                    wire:model="location_area"
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm"
                >
                    <option value="">Pilih kawasan</option>
                    <option value="Kuala Lumpur">Kuala Lumpur</option>
                    <option value="Selangor">Selangor</option>
                    <option value="Putrajaya">Putrajaya</option>
                </select>
                @error('location_area') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <p class="block text-sm font-medium text-gray-700 mb-2">Kawasan Liputan</p>
                <div class="flex flex-wrap gap-2">
                    @foreach (['Kuala Lumpur', 'Selangor', 'Putrajaya'] as $area)
                        <label class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-gray-200 text-sm cursor-pointer hover:bg-gray-50 has-[:checked]:bg-brand-50 has-[:checked]:border-brand-300">
                            <input type="checkbox" wire:model="coverage_areas" value="{{ $area }}" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                            {{ $area }}
                        </label>
                    @endforeach
                </div>
                @error('coverage_areas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </x-ui.card>

        <x-ui.card class="p-6 space-y-5">
            <h2 class="text-lg font-semibold text-gray-900">Maklumat Hubungan</h2>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telefon</label>
                <input
                    id="phone"
                    type="tel"
                    wire:model="phone"
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm"
                    placeholder="0123456789"
                >
                @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp</label>
                <input
                    id="whatsapp_number"
                    type="tel"
                    wire:model="whatsapp_number"
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm"
                    placeholder="0123456789"
                >
                @error('whatsapp_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="instagram_handle" class="block text-sm font-medium text-gray-700 mb-1">Instagram (opsyenal)</label>
                <input
                    id="instagram_handle"
                    type="text"
                    wire:model="instagram_handle"
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 text-sm"
                    placeholder="@username"
                >
                @error('instagram_handle') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </x-ui.card>

        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <x-ui.button type="submit" variant="primary" size="lg" class="w-full sm:w-auto">
                <span wire:loading.remove wire:target="submit">Hantar Profil</span>
                <span wire:loading wire:target="submit">Menghantar...</span>
            </x-ui.button>
        </div>
    </form>
</div>
