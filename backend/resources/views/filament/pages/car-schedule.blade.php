<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold">Timeline Jadwal Armada</h2>
                <div class="flex space-x-2">
                    <span class="flex items-center text-xs"><span class="w-3 h-3 bg-primary-500 rounded-full mr-1"></span> Booking</span>
                    <span class="flex items-center text-xs"><span class="w-3 h-3 bg-danger-500 rounded-full mr-1"></span> Maintenance</span>
                </div>
            </div>
        </x-filament::section>

        <div class="overflow-x-auto">
            <div class="min-w-[1000px] bg-white dark:bg-gray-900 rounded-xl shadow overflow-hidden">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <th class="p-4 text-left w-64 sticky left-0 bg-gray-50 dark:bg-gray-800 z-10">Mobil / Tanggal</th>
                            @for($i = 0; $i < 30; $i++)
                                <th class="p-2 text-center text-xs border-l border-gray-100 dark:border-gray-700">
                                    {{ now()->addDays($i)->format('d') }}<br>
                                    <span class="text-[10px] opacity-60">{{ now()->addDays($i)->format('D') }}</span>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($cars as $car)
                            <tr>
                                <td class="p-4 text-sm font-medium sticky left-0 bg-white dark:bg-gray-900 z-10 border-r border-gray-100 dark:border-gray-700">
                                    {{ $car->car_name }}<br>
                                    <span class="text-xs opacity-60">{{ $car->plate_number }}</span>
                                </td>
                                @for($i = 0; $i < 30; $i++)
                                    @php
                                        $currentDate = now()->addDays($i)->format('Y-m-d');
                                        $isBooked = $car->bookings->contains(fn($b) => 
                                            $currentDate >= $b->pickup_date->format('Y-m-d') && 
                                            $currentDate <= $b->return_date->format('Y-m-d')
                                        );
                                        // You could also check maintenance here
                                    @endphp
                                    <td class="p-2 border-l border-gray-100 dark:border-gray-700">
                                        @if($isBooked)
                                            <div class="h-6 bg-primary-500 rounded shadow-sm flex items-center justify-center" title="Booked">
                                                <x-filament::icon icon="heroicon-m-check" class="w-3 h-3 text-white" />
                                            </div>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
