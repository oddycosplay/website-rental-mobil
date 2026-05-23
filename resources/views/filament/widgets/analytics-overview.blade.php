<x-filament-widgets::widget>
    @php
        $dailyChart    = $this->dailyRevenueChart;
        $vehicleChart  = $this->vehicleTypeChart;
        $currentMonth  = now()->translatedFormat('M Y');
        $stats         = $this->stats;
    @endphp

    <div class="space-y-5">
        {{-- Filter Bar --}}
        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-4">
            <div class="flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                        Periode Waktu
                    </label>
                    <select wire:model.live="period" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm px-3 py-2 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                        @foreach($this->getPeriodOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1.5">
                        Tipe Layanan
                    </label>
                    <select wire:model.live="serviceType" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 text-sm px-3 py-2 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                        @foreach($this->getServiceOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button wire:click="$refresh" class="flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm">
                    <svg wire:loading.class="animate-spin" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Update Data
                </button>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            {{-- Revenue --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-5 flex flex-col gap-2">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                <div class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Pendapatan</div>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="text-xs text-teal-600 dark:text-teal-400 font-medium">Periode Terpilih</span>
                </div>
            </div>
            {{-- Booking --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-5 flex flex-col gap-2">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_bookings']) }}</div>
                <div class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total Booking</div>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Transaksi Berhasil</span>
                </div>
            </div>
            {{-- Duration --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-5 flex flex-col gap-2">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['avg_duration'] }} Hari</div>
                <div class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Rata-rata Durasi</div>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">Per Transaksi</span>
                </div>
            </div>
            {{-- Refund --}}
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-5 flex flex-col gap-2">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['refunded']) }}</div>
                <div class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Refund / Batal</div>
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="text-xs text-red-600 dark:text-red-400 font-medium">Transaksi Refund</span>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            <div class="xl:col-span-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Pendapatan Harian ({{ $currentMonth }})</h3>
                </div>
                <div class="relative" style="height: 280px;">
                    <canvas id="bar-{{ $this->getId() }}" data-labels="{{ json_encode($dailyChart['labels']) }}" data-values="{{ json_encode($dailyChart['data']) }}"></canvas>
                </div>
            </div>
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Booking Berdasarkan Kendaraan</h3>
                <div class="relative flex justify-center items-center" style="height: 220px;">
                    <canvas id="doughnut-{{ $this->getId() }}" data-labels="{{ json_encode($vehicleChart['labels']) }}" data-values="{{ json_encode($vehicleChart['data']) }}" data-colors="{{ json_encode($vehicleChart['backgroundColors']) }}"></canvas>
                </div>
                <div class="mt-4 flex flex-wrap gap-x-4 gap-y-2 justify-center">
                    @foreach($vehicleChart['labels'] as $i => $label)
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full inline-block" @style(['background-color: ' . ($vehicleChart['backgroundColors'][$i] ?? '#ccc')])></span>
                            <span class="text-xs text-gray-600 dark:text-gray-300">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        const bId = 'bar-' + $wire.id;
        const dId = 'doughnut-' + $wire.id;
        let bInst = null;
        let dInst = null;

        const fmt = (v) => {
            if (v >= 1000000) return 'Rp ' + (v / 1000000).toFixed(1).replace(/\.0$/, '') + ' Jt';
            if (v >= 1000) return 'Rp ' + (v / 1000).toFixed(0) + ' Rb';
            return 'Rp ' + v;
        };

        const init = () => {
            const dark = document.documentElement.classList.contains('dark');
            const gCol = dark ? 'rgba(255,255,255,0.07)' : 'rgba(0,0,0,0.07)';
            const lCol = dark ? '#9ca3af' : '#6b7280';

            const bEl = document.getElementById(bId);
            if (bEl) {
                const bL = JSON.parse(bEl.dataset.labels || '[]');
                const bV = JSON.parse(bEl.dataset.values || '[]');
                if (bInst) bInst.destroy();
                bInst = new Chart(bEl, {
                    type: 'bar',
                    data: { labels: bL, datasets: [{ label: 'Pendapatan', data: bV, backgroundColor: '#D4A017', borderRadius: 5, borderSkipped: false }] },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => ' ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(c.raw) } } },
                        scales: { x: { grid: { display: false }, ticks: { color: lCol, font: { size: 11 } } }, y: { grid: { color: gCol }, ticks: { color: lCol, font: { size: 11 }, callback: (v) => fmt(v) }, border: { display: false } } }
                    }
                });
            }

            const dEl = document.getElementById(dId);
            if (dEl) {
                const dL = JSON.parse(dEl.dataset.labels || '[]');
                const dV = JSON.parse(dEl.dataset.values || '[]');
                const dC = JSON.parse(dEl.dataset.colors || '[]');
                if (dInst) dInst.destroy();
                dInst = new Chart(dEl, {
                    type: 'doughnut',
                    data: { labels: dL, datasets: [{ data: dV, backgroundColor: dC, borderWidth: 3, borderColor: dark ? '#1f2937' : '#ffffff', hoverOffset: 8 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '68%', plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => ' ' + c.label + ': ' + c.raw + ' booking' } } } }
                });
            }
        };

        $wire.on('analyticsUpdated', () => setTimeout(init, 80));
        setTimeout(init, 120);
    </script>
    @endscript
</x-filament-widgets::widget>
