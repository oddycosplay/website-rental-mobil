<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <form wire:submit="generateReport" class="flex items-center space-x-4">
                <div class="flex-1">
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="number"
                            wire:model="filter_year"
                            placeholder="Tahun"
                        />
                    </x-filament::input.wrapper>
                </div>
                <x-filament::button type="submit">
                    Filter
                </x-filament::button>
                <x-filament::button color="success" icon="heroicon-o-document-text" wire:click="exportExcel">
                    Excel
                </x-filament::button>
                <x-filament::button color="danger" icon="heroicon-o-document-arrow-down" wire:click="exportPdf">
                    PDF
                </x-filament::button>
            </form>
        </x-filament::section>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <th class="p-4 font-semibold text-sm">Bulan</th>
                        <th class="p-4 font-semibold text-sm">Pendapatan</th>
                        <th class="p-4 font-semibold text-sm">Pengeluaran</th>
                        <th class="p-4 font-semibold text-sm text-right">Laba/Rugi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($report_data as $data)
                        <tr>
                            <td class="p-4 text-sm">{{ $data['month'] }}</td>
                            <td class="p-4 text-sm text-success-600">Rp {{ number_format($data['income'], 0, ',', '.') }}</td>
                            <td class="p-4 text-sm text-danger-600">Rp {{ number_format($data['expense'], 0, ',', '.') }}</td>
                            <td class="p-4 text-sm text-right {{ $data['profit'] >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                                <strong>Rp {{ number_format($data['profit'], 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 dark:bg-gray-900 font-bold">
                        <td class="p-4 text-sm">TOTAL</td>
                        <td class="p-4 text-sm text-success-600">Rp {{ number_format(collect($report_data)->sum('income'), 0, ',', '.') }}</td>
                        <td class="p-4 text-sm text-danger-600">Rp {{ number_format(collect($report_data)->sum('expense'), 0, ',', '.') }}</td>
                        <td class="p-4 text-sm text-right {{ collect($report_data)->sum('profit') >= 0 ? 'text-success-600' : 'text-danger-600' }}">
                            Rp {{ number_format(collect($report_data)->sum('profit'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-filament-panels::page>
