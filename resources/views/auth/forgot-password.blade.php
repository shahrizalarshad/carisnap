<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl font-heading font-bold text-gray-900">Lupa Kata Laluan</h1>
    </div>

    <div class="mb-4 text-sm text-gray-600">
        Tiada masalah. Beritahu kami alamat e-mel anda dan kami akan hantar pautan set semula kata laluan.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="E-mel" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Hantar Pautan Set Semula
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
