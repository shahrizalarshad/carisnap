<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Maklumat Profil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Kemas kini nama, e-mel, dan nombor telefon akaun anda.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="E-mel" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        Alamat e-mel anda belum disahkan.

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Klik di sini untuk hantar semula e-mel pengesahan.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            Pautan pengesahan baharu telah dihantar ke alamat e-mel anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if ($user->role === \App\Enums\UserRole::Client)
            <div>
                <x-input-label for="phone" value="Telefon" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
                <p class="mt-1 text-xs text-gray-500">Nombor ini digunakan untuk pautkan tempahan tetamu ke akaun anda.</p>
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Disimpan.</p>
            @endif
        </div>
    </form>
</section>
