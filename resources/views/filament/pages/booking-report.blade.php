<x-filament-panels::page>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <div class="space-y-6">
        <!-- Filter Section -->
        <x-filament::section class="mb-6">
            <form wire:submit="generateReport" class="flex flex-col gap-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                    <!-- Tahun -->
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun</label>
                        <select wire:model.live="filter_year" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                            @foreach(range(now()->year - 2, now()->year + 1) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Bulan -->
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bulan</label>
                        <select wire:model.live="filter_month" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                            <option value="all">Semua Bulan (Tahunan)</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ now()->month($m)->format('F') }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal -->
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                        <select wire:model.live="filter_day" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm disabled:opacity-50" {{ $filter_month === 'all' ? 'disabled' : '' }}>
                            <option value="all">Semua Tanggal</option>
                            @if($filter_month !== 'all')
                                @php
                                    $daysInMonth = \Carbon\Carbon::createFromDate($filter_year, $filter_month, 1)->daysInMonth;
                                @endphp
                                @foreach(range(1, $daysInMonth) as $day)
                                    <option value="{{ $day }}">{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Tipe Sewa (Supir/Lepas Kunci) -->
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Sewa</label>
                        <select wire:model.live="filter_driver" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                            <option value="all">Semua Tipe</option>
                            <option value="0">Lepas Kunci</option>
                            <option value="1">Dengan Supir</option>
                        </select>
                    </div>

                    <!-- Layanan (Harian/Bulanan) -->
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Layanan</label>
                        <select wire:model.live="filter_service" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                            <option value="all">Semua Layanan</option>
                            <option value="daily">Sewa Harian</option>
                            <option value="monthly">Sewa Bulanan</option>
                        </select>
                    </div>

                    <!-- Area (Jabodetabek) -->
                    <div class="w-full">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Wilayah</label>
                        <select wire:model.live="filter_area" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm">
                            <option value="all">Semua Wilayah</option>
                            <option value="jabodetabek">Jabodetabek</option>
                            <option value="luar_jabodetabek">Luar Jabodetabek</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center space-x-4 mt-2">
                    <x-filament::button color="success" icon="heroicon-o-document-text" wire:click="exportExcel">
                        Export Excel
                    </x-filament::button>
                    <x-filament::button color="danger" icon="heroicon-o-document-arrow-down" wire:click="exportPdf">
                        Export PDF
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        <!-- Summary Section -->
        <x-filament::section>
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 md:gap-0 md:divide-x divide-gray-200 dark:divide-gray-700">
                <div class="flex flex-col items-center justify-center p-4 w-full md:w-1/4">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400 text-center">Total Booking</span>
                    <span class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $summary['total_bookings'] ?? 0 }}</span>
                </div>
                
                <div class="flex flex-col items-center justify-center p-4 w-full md:w-1/4">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400 text-center">Booking Selesai</span>
                    <span class="text-3xl font-bold text-success-600 dark:text-success-400 mt-2">{{ $summary['completed_bookings'] ?? 0 }}</span>
                </div>
                
                <div class="flex flex-col items-center justify-center p-4 w-full md:w-1/4">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400 text-center">Booking Batal</span>
                    <span class="text-3xl font-bold text-danger-600 dark:text-danger-400 mt-2">{{ $summary['cancelled_bookings'] ?? 0 }}</span>
                </div>

                <div class="flex flex-col items-center justify-center p-4 w-full md:w-1/4">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400 text-center">Total Pendapatan</span>
                    <span class="text-2xl font-bold text-primary-600 dark:text-primary-400 mt-2">Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </x-filament::section>

        <!-- Diagram Section -->
        <div class="flex flex-col lg:flex-row gap-6 items-start">
            <div class="w-full lg:w-2/3" style="flex: 2;">
                <x-filament::section heading="Grafik Pendapatan">
                    <div x-data="{
                        chart: null,
                        init() {
                            let options = {
                                chart: { 
                                    type: 'area', 
                                    height: 350, 
                                    toolbar: { show: false },
                                    fontFamily: 'inherit'
                                },
                                series: [{
                                    name: 'Pendapatan (Rp)',
                                    data: @js(array_column($filter_month === 'all' ? $monthly_data : $daily_data, 'revenue'))
                                }],
                                xaxis: {
                                    categories: @js(array_column($filter_month === 'all' ? $monthly_data : $daily_data, $filter_month === 'all' ? 'month' : 'date')),
                                    labels: {
                                        style: {
                                            colors: '#6b7280'
                                        }
                                    }
                                },
                                yaxis: {
                                    labels: {
                                        formatter: function (value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        },
                                        style: {
                                            colors: '#6b7280'
                                        }
                                    }
                                },
                                colors: ['#EAB308'],
                                fill: { 
                                    type: 'gradient', 
                                    gradient: { 
                                        shadeIntensity: 1, 
                                        opacityFrom: 0.4, 
                                        opacityTo: 0.05, 
                                        stops: [0, 90, 100] 
                                    } 
                                },
                                dataLabels: { enabled: false },
                                stroke: { curve: 'smooth', width: 3 },
                                tooltip: {
                                    y: {
                                        formatter: function (value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                }
                            };
                            this.chart = new ApexCharts(this.$refs.chart, options);
                            this.chart.render();
                        }
                    }" class="w-full relative">
                        <div x-ref="chart"></div>
                    </div>
                </x-filament::section>
            </div>

            <div class="w-full lg:w-1/3" style="flex: 1;">
                <x-filament::section heading="Status Booking">
                    <div x-data="{
                        pieChart: null,
                        init() {
                            let options = {
                                chart: { 
                                    type: 'donut', 
                                    height: 350,
                                    fontFamily: 'inherit'
                                },
                                series: [{{ $summary['completed_bookings'] ?? 0 }}, {{ $summary['cancelled_bookings'] ?? 0 }}, {{ $summary['pending_bookings'] ?? 0 }}],
                                labels: ['Selesai', 'Batal', 'Proses/Menunggu'],
                                colors: ['#10B981', '#EF4444', '#F59E0B'],
                                dataLabels: { 
                                    enabled: true,
                                    formatter: function (val) {
                                        return val.toFixed(1) + '%'
                                    }
                                },
                                legend: { 
                                    position: 'bottom',
                                    horizontalAlign: 'center'
                                },
                                plotOptions: {
                                    pie: {
                                        donut: {
                                            size: '65%'
                                        }
                                    }
                                }
                            };
                            this.pieChart = new ApexCharts(this.$refs.pieChart, options);
                            this.pieChart.render();
                        }
                    }" class="w-full flex justify-center items-center h-full">
                        <div x-ref="pieChart" class="w-full"></div>
                    </div>
                </x-filament::section>
            </div>
        </div>

        <!-- Data Table -->
        @if($filter_month === 'all')
        <x-filament::section heading="Laporan Bulanan (Tahun {{ $filter_year }})">
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Bulan</th>
                            <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-300 text-center">Jumlah Booking</th>
                            <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-300 text-right">Pendapatan (Paid)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($monthly_data as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition duration-150">
                                <td class="p-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $data['month'] }}</td>
                                <td class="p-4 text-sm text-gray-800 dark:text-gray-200 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-300">
                                        {{ $data['total_bookings'] }}
                                    </span>
                                </td>
                                <td class="p-4 text-sm font-semibold text-success-600 dark:text-success-400 text-right">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                            <td class="p-4 text-sm text-gray-800 dark:text-gray-200 uppercase tracking-wider">Total Tahun Ini</td>
                            <td class="p-4 text-sm text-gray-800 dark:text-gray-200 text-center text-lg">{{ collect($monthly_data)->sum('total_bookings') }}</td>
                            <td class="p-4 text-sm text-success-600 dark:text-success-400 text-right text-lg">Rp {{ number_format(collect($monthly_data)->sum('revenue'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-filament::section>
        @else
        <x-filament::section heading="Laporan Harian (Bulan {{ now()->month((int)$filter_month)->format('F') }} {{ $filter_year }})">
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-300">Tanggal</th>
                            <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-300 text-center">Jumlah Booking</th>
                            <th class="p-4 text-sm font-semibold text-gray-600 dark:text-gray-300 text-right">Pendapatan (Paid)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($daily_data as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition duration-150">
                                <td class="p-4 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $data['date'] }}</td>
                                <td class="p-4 text-sm text-gray-800 dark:text-gray-200 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-300">
                                        {{ $data['total_bookings'] }}
                                    </span>
                                </td>
                                <td class="p-4 text-sm font-semibold text-success-600 dark:text-success-400 text-right">Rp {{ number_format($data['revenue'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="font-bold bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                            <td class="p-4 text-sm text-gray-800 dark:text-gray-200 uppercase tracking-wider">Total Bulan Ini</td>
                            <td class="p-4 text-sm text-gray-800 dark:text-gray-200 text-center text-lg">{{ collect($daily_data)->sum('total_bookings') }}</td>
                            <td class="p-4 text-sm text-success-600 dark:text-success-400 text-right text-lg">Rp {{ number_format(collect($daily_data)->sum('revenue'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
