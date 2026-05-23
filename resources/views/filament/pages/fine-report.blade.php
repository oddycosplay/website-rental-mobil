<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold">Ringkasan Denda</h2>
                    <p class="text-sm text-gray-500">Daftar booking dengan denda keterlambatan.</p>
                </div>
                <div class="flex items-center space-x-4">
                    <x-filament::button color="success" icon="heroicon-o-document-text" wire:click="exportExcel">
                        Excel
                    </x-filament::button>
                    <x-filament::button color="danger" icon="heroicon-o-document-arrow-down" wire:click="exportPdf">
                        PDF
                    </x-filament::button>
                </div>
            </div>
        </x-filament::section>
 
        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <th class="p-4 font-semibold text-sm">Booking Code</th>
                        <th class="p-4 font-semibold text-sm">Pelanggan</th>
                        <th class="p-4 font-semibold text-sm">Mobil</th>
                        <th class="p-4 font-semibold text-sm">Tgl Kembali</th>
                        <th class="p-4 font-semibold text-sm text-right">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($fines as $booking)
                        <tr>
                            <td class="p-4 text-sm font-medium">{{ $booking->booking_code }}</td>
                            <td class="p-4 text-sm">{{ $booking->customer->name ?? '-' }}</td>
                            <td class="p-4 text-sm">{{ $booking->car->car_name ?? '-' }}</td>
                            <td class="p-4 text-sm">{{ $booking->return_date->format('d M Y H:i') }}</td>
                            <td class="p-4 text-sm text-right text-danger-600 font-bold">
                                Rp {{ number_format($booking->late_fee, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500 text-sm">
                                Tidak ada data denda keterlambatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($fines) > 0)
                <tfoot>
                    <tr class="bg-gray-50 dark:bg-gray-900 font-bold">
                        <td colspan="4" class="p-4 text-sm text-right">TOTAL DENDA</td>
                        <td class="p-4 text-sm text-right text-danger-600">
                            Rp {{ number_format(collect($fines)->sum('late_fee'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</x-filament-panels::page>
