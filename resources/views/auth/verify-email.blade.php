<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Terima kasih kerana mendaftar! Sebelum bermula, sila sahkan alamat e-mel anda dengan mengklik pautan yang kami hantar. Jika anda tidak menerima e-mel tersebut, kami boleh hantar semula.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            Pautan pengesahan baharu telah dihantar ke alamat e-mel yang anda gunakan semasa pendaftaran.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Hantar Semula E-mel Pengesahan
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                Log Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
