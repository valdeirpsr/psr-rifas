<x-filament-widgets::widget class="{{ $this->hasWinner() ? '' : 'hidden' }}">
    <x-filament::section class="!bg-green-500 dark:!bg-green-900">
        <div class="flex items-center gap-x-2">
            <span class="text-sm font-medium text-white dark:text-gray-50">
                Vencedor(es)
            </span>
        </div>

        <div class="mt-2 text-2xl font-semibold tracking-tight text-white">
            <ul>
                @foreach ($this->getWinners() as $winner)
                <li>{{ $winner['position'] }}º Prêmio -
                    {{ $winner['order']['customer_fullname'] }} -
                    {{ preg_replace('/^([\d]{2})([\d]{5})([\d]+)/', '($1) $2-$3', $winner['order']['customer_telephone']) }}
                </li>
                @endforeach
            </ul>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
