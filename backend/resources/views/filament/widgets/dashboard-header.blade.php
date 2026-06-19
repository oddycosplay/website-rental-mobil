<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex-1 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-2 mb-1">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                        Welcome back, {{ auth()->user()->name }}! 👋
                    </h2>
                    @if($tenant = \Filament\Facades\Filament::getTenant())
                        <x-filament::badge color="info" size="sm">
                            Branch: {{ $tenant->name }}
                        </x-filament::badge>
                    @endif
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ now()->format('l, d F Y') }} • Summary of your business performance today.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <x-filament::button
                    icon="heroicon-m-plus"
                    tag="a"
                    href="{{ \App\Filament\Resources\Bookings\BookingResource::getUrl('create') }}"
                    color="primary"
                    outlined
                >
                    New Booking
                </x-filament::button>
                
                <x-filament::button
                    icon="heroicon-m-document-chart-bar"
                    tag="a"
                    href="{{ \App\Filament\Pages\FineReport::getUrl() ?? '#' }}"
                    color="gray"
                    outlined
                >
                    Business Reports
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
