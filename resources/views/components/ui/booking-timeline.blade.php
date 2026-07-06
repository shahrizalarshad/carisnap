@props(['status'])

@php
    $steps = [
        ['key' => 'pending', 'label' => 'Permintaan dihantar'],
        ['key' => 'quoted', 'label' => 'Sebut harga'],
        ['key' => 'accepted', 'label' => 'Diterima'],
    ];

    $order = ['pending', 'quoted', 'accepted', 'declined', 'expired'];
    $currentIndex = array_search($status->value, $order, true);
    if ($currentIndex === false) {
        $currentIndex = 0;
    }
    if (in_array($status->value, ['declined', 'expired'], true)) {
        $currentIndex = 1;
    }
@endphp

<ol class="flex items-center w-full text-sm">
    @foreach ($steps as $index => $step)
        @php
            $isComplete = $index < $currentIndex || ($status === \App\Enums\BookingStatus::Accepted && $index <= 2);
            $isCurrent = ($index === $currentIndex && ! in_array($status->value, ['declined', 'expired', 'accepted']))
                || ($status === \App\Enums\BookingStatus::Accepted && $index === 2)
                || ($status === \App\Enums\BookingStatus::Quoted && $index === 1);
        @endphp
        <li class="flex items-center {{ $loop->last ? '' : 'flex-1' }}">
            <div class="flex flex-col items-center min-w-0">
                <span @class([
                    'flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold',
                    'bg-brand-600 text-white' => $isComplete || $isCurrent,
                    'bg-gray-100 text-gray-400' => ! $isComplete && ! $isCurrent,
                ])>
                    @if ($isComplete && ! $isCurrent)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    @else
                        {{ $index + 1 }}
                    @endif
                </span>
                <span @class([
                    'mt-2 text-center text-xs leading-tight max-w-[4.5rem]',
                    'font-semibold text-brand-700' => $isCurrent,
                    'text-gray-500' => ! $isCurrent,
                ])>{{ $step['label'] }}</span>
            </div>
            @if (! $loop->last)
                <div @class([
                    'h-0.5 flex-1 mx-2 rounded',
                    'bg-brand-300' => $index < $currentIndex,
                    'bg-gray-200' => $index >= $currentIndex,
                ])></div>
            @endif
        </li>
    @endforeach
</ol>
