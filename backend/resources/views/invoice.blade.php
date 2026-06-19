@extends('layouts.app')
@section('title', 'Invoice #' . $booking->booking_code)

@section('content')
<div class="pt-32 pb-16 bg-slate-900 min-h-screen">
    <div class="max-w-4xl mx-auto px-6">
        
        <!-- Success Alert -->
        <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-2xl p-6 mb-10 flex items-center gap-6 animate-[fadeInUp_0.5s_ease_both]">
            <div class="w-16 h-16 rounded-full bg-emerald-500 flex items-center justify-center text-white text-3xl shadow-lg shadow-emerald-500/20">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-white mb-1">Pesanan Berhasil Dibuat!</h2>
                <p class="text-slate-400">Silakan lakukan pembayaran sesuai instruksi di bawah untuk mengonfirmasi pesanan Anda.</p>
            </div>
        </div>

        <!-- Invoice Card -->
        <div id="printableArea" class="bg-white rounded-3xl overflow-hidden shadow-2xl shadow-black/40 text-slate-900">
            <!-- Header -->
            <div class="bg-slate-900 p-8 flex justify-between items-center text-white">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-gold flex items-center justify-center text-slate-900 text-sm font-black">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="font-poppins font-extrabold text-lg tracking-tight">Siliwangi<span class="text-gold">Rental</span></div>
                    </div>
                    <p class="text-slate-400 text-xs">Premium Car Rental Service</p>
                </div>
                <div class="text-right">
                    <h1 class="text-2xl font-black text-gold uppercase tracking-tighter">INVOICE</h1>
                    <p class="text-slate-400 text-xs mt-1">#{{ $booking->booking_code }}</p>
                </div>
            </div>

            <div class="p-8 lg:p-12">
                <!-- Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Informasi Pelanggan</h4>
                        <div class="space-y-2">
                            <div class="font-bold text-lg">{{ $booking->customer->user->name }}</div>
                            <div class="text-sm text-slate-600 flex items-center gap-2"><i class="fas fa-envelope text-slate-400 w-4"></i> {{ $booking->customer->user->email }}</div>
                            <div class="text-sm text-slate-600 flex items-center gap-2"><i class="fas fa-phone text-slate-400 w-4"></i> {{ $booking->customer->phone }}</div>
                            <div class="text-sm text-slate-600 flex items-center gap-2"><i class="fas fa-id-card text-slate-400 w-4"></i> {{ $booking->customer->nik }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Status & Waktu</h4>
                        <div class="space-y-2">
                            <div><span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold uppercase tracking-wider border border-amber-200">Menunggu Pembayaran</span></div>
                            <div class="text-sm text-slate-600 mt-3">Tanggal Pesan: <span class="font-semibold">{{ $booking->created_at->format('d M Y, H:i') }}</span></div>
                            <div class="text-sm text-slate-600">Batas Bayar: <span class="font-semibold text-red-500">{{ $booking->expired_at->format('d M Y, H:i') }}</span></div>
                        </div>
                    </div>
                </div>

                <!-- Booking Details Table -->
                <div class="border rounded-2xl overflow-hidden mb-12">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 border-b">
                            <tr>
                                <th class="px-6 py-4 font-bold text-slate-700">Deskripsi Layanan</th>
                                <th class="px-6 py-4 font-bold text-slate-700 text-center">Durasi</th>
                                <th class="px-6 py-4 font-bold text-slate-700 text-right">Harga Satuan</th>
                                <th class="px-6 py-4 font-bold text-slate-700 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @if($booking->items && $booking->items->count() > 0)
                                @foreach($booking->items as $item)
                                <tr>
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-slate-900">{{ $item->car->car_name }}</div>
                                        <div class="text-xs text-slate-500 mt-1 uppercase tracking-tighter">Sewa Mobil {{ $booking->with_driver ? '+ Supir' : '(Lepas Kunci)' }}</div>
                                        <div class="text-[10px] text-slate-400 mt-2">Cabang: {{ $booking->branch->name }}</div>
                                        <div class="text-[10px] text-slate-400">Pickup: {{ $booking->pickup_date->format('d M Y') }}</div>
                                        <div class="text-[10px] text-slate-400">Return: {{ $booking->return_date->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-5 text-center font-medium">{{ $booking->total_day }} Hari</td>
                                    <td class="px-6 py-5 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-5 text-right font-bold">Rp {{ number_format($item->price * $booking->total_day, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-slate-900">{{ $booking->car->car_name }}</div>
                                        <div class="text-xs text-slate-500 mt-1 uppercase tracking-tighter">Sewa Mobil {{ $booking->with_driver ? '+ Supir' : '(Lepas Kunci)' }}</div>
                                        <div class="text-[10px] text-slate-400 mt-2">Cabang: {{ $booking->branch->name }}</div>
                                        <div class="text-[10px] text-slate-400">Pickup: {{ $booking->pickup_date->format('d M Y') }}</div>
                                        <div class="text-[10px] text-slate-400">Return: {{ $booking->return_date->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-5 text-center font-medium">{{ $booking->total_day }} Hari</td>
                                    <td class="px-6 py-5 text-right">Rp {{ number_format($booking->price, 0, ',', '.') }}</td>
                                    <td class="px-6 py-5 text-right font-bold">Rp {{ number_format($booking->price * $booking->total_day, 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            @if($booking->driver_price > 0)
                            <tr>
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-900">Layanan Driver Profesional</div>
                                    <div class="text-xs text-slate-500 mt-1 italic">Flat rate per hari</div>
                                </td>
                                <td class="px-6 py-5 text-center font-medium">{{ $booking->total_day }} Hari</td>
                                <td class="px-6 py-5 text-right">Rp {{ number_format($booking->driver_price / $booking->total_day, 0, ',', '.') }}</td>
                                <td class="px-6 py-5 text-right font-bold">Rp {{ number_format($booking->driver_price, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-slate-900 text-white">
                            <tr>
                                <td colspan="3" class="px-6 py-5 text-right font-bold uppercase tracking-widest text-xs text-slate-400">Grand Total</td>
                                <td class="px-6 py-5 text-right font-poppins font-black text-2xl text-gold italic">Rp {{ number_format($booking->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Payment Instructions -->
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 mb-8">
                    <h4 class="font-bold text-slate-900 mb-4 flex items-center gap-2"><i class="fas fa-credit-card text-gold"></i> Instruksi Pembayaran</h4>
                    <p class="text-sm text-slate-600 mb-6 leading-relaxed">Silakan transfer tepat sesuai nominal <strong>Grand Total</strong> di atas ke rekening berikut:</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-white border flex items-center gap-4">
                            <div class="w-12 h-8 bg-blue-600 rounded flex items-center justify-center text-white font-black italic text-xs">BCA</div>
                            <div>
                                <div class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Bank BCA</div>
                                <div class="font-bold text-slate-900">1234567890</div>
                                <div class="text-xs text-slate-500">A/N Siliwangi Rental Transnusa</div>
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-white border flex items-center gap-4">
                            <div class="w-12 h-8 bg-orange-500 rounded flex items-center justify-center text-white font-black italic text-xs">MDR</div>
                            <div>
                                <div class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Bank Mandiri</div>
                                <div class="font-bold text-slate-900">0987654321</div>
                                <div class="text-xs text-slate-500">A/N Siliwangi Rental Transnusa</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 rounded-xl bg-gold/10 border border-gold/20 text-xs text-slate-600 leading-loose">
                        <i class="fas fa-exclamation-triangle text-gold mr-2"></i> Konfirmasi pembayaran akan dilakukan otomatis oleh sistem dalam 10-30 menit setelah dana masuk. Jika status tidak berubah, hubungi <a href="https://wa.me/628973716530" class="text-gold font-bold underline">WhatsApp Admin</a>.
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="p-8 bg-slate-50 border-t flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex gap-4">
                    <button onclick="window.print()" class="px-6 py-3 rounded-xl border-2 border-slate-200 text-slate-600 font-bold hover:bg-white transition-all flex items-center gap-2">
                        <i class="fas fa-print"></i> Cetak Invoice
                    </button>
                    <a href="{{ route('home') }}" class="px-6 py-3 rounded-xl border-2 border-slate-200 text-slate-600 font-bold hover:bg-white transition-all">
                        Beranda
                    </a>
                </div>
                
                @php $latestPayment = $booking->payments->where('payment_status', 'pending')->first(); @endphp
                
                @if($booking->payment_status == 'unpaid' && $latestPayment && $latestPayment->snap_token)
                    <button id="pay-button" class="px-10 py-3 rounded-xl bg-gold text-slate-900 font-black hover:shadow-xl hover:shadow-gold/30 hover:-translate-y-1 transition-all flex items-center gap-2">
                        <i class="fas fa-credit-card"></i> BAYAR SEKARANG (OTOMATIS)
                    </button>
                @else
                    <a href="https://wa.me/628973716530?text=Halo%20Admin%2C%20saya%20ingin%20konfirmasi%20pembayaran%20untuk%20invoice%20{{ $booking->booking_code }}" target="_blank" class="px-8 py-3 rounded-xl bg-[#25D366] text-white font-bold hover:shadow-lg hover:shadow-emerald-500/20 transition-all flex items-center gap-2">
                        <i class="fab fa-whatsapp text-lg"></i> Konfirmasi via WhatsApp
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@section('scripts')
@php $latestPayment = $booking->payments->where('payment_status', 'pending')->first(); @endphp
@if($latestPayment && $latestPayment->snap_token)
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            window.snap.pay('{{ $latestPayment->snap_token }}', {
                onSuccess: function (result) {
                    window.location.reload();
                },
                onPending: function (result) {
                    window.location.reload();
                },
                onError: function (result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function () {
                    alert('Anda menutup jendela pembayaran sebelum selesai.');
                }
            });
        });
    </script>
@endif
@endsection

<style>
@media print {
    body * { visibility: hidden; }
    #printableArea, #printableArea * { visibility: visible; }
    #printableArea { position: absolute; left: 0; top: 0; width: 100%; margin: 0; padding: 0; }
    .footer-buttons { display: none !important; }
}
</style>
@endsection
