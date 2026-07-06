@php
    $steps = $this->getSteps();
    $completed = collect($steps)->filter(fn ($step) => $step['done'])->count();
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Semak Profil Anda
        </x-slot>

        <x-slot name="description">
            @if ($this->isComplete())
                Tahniah — profil anda lengkap dan sedia tarik pelanggan.
            @else
                {{ $completed }}/{{ count($steps) }} langkah siap. Lengkapkan baki untuk profil lebih menarik.
            @endif
        </x-slot>

        @if ($steps === [])
            <p class="text-sm text-gray-600">Profil belum dicipta.</p>
        @else
            <ol class="space-y-3">
                @foreach ($steps as $step)
                    <li class="flex items-start gap-3 rounded-lg border border-gray-200 px-4 py-3 {{ $step['done'] ? 'bg-success-50/50 border-success-200' : 'bg-white' }}">
                        @if ($step['done'])
                            <x-filament::icon icon="heroicon-o-check-circle" class="h-5 w-5 text-success-600 shrink-0 mt-0.5" />
                        @else
                            <x-filament::icon icon="heroicon-o-ellipsis-horizontal-circle" class="h-5 w-5 text-gray-400 shrink-0 mt-0.5" />
                        @endif
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-950">{{ $step['label'] }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $step['hint'] }}</p>
                            @if (! $step['done'] && $step['url'])
                                <a href="{{ $step['url'] }}" class="mt-2 inline-flex text-xs font-medium text-primary-600 hover:underline">
                                    Buat sekarang →
                                </a>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
