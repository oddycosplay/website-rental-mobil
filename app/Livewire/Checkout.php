<?php

namespace App\Livewire;

use App\Models\Store;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Promo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

class Checkout extends Component
{
    use WithFileUploads;

    /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Car[] */
    public $cars = [];
    public $car_names = '';
    public int $step = 1;

    // Step 1: Konfigurasi Sewa
    public ?string $pickup_date = null;
    public ?string $return_date = null;
    public string $need_type = 'jemput'; // jemput or antar
    public ?string $pickup_location = null;
    public ?string $return_location = null;
    public string $rental_type = 'daily'; // daily, monthly
    public ?int $branch_id = null;
    public string $delivery_type = 'none'; // none, standard, airport
    public string $pickup_type = 'none'; // none, standard, airport
    public float $delivery_fee = 0;
    public float $pickup_fee = 0;

    // Step 2: Identitas
    public ?string $name = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $nik = null;
    public ?string $sim_number = null;
    public ?string $address = null;

    // Step 3: Verifikasi Dokumen
    public mixed $ktp_image = null;
    public mixed $sim_image = null;
    public mixed $kk_image = null;
    public mixed $npwp_image = null;
    public mixed $pelajar_image = null;
    public mixed $mahasiswa_image = null;

    // Step 4: Opsi Driver & Catatan
    public bool $with_driver = false; 
    public ?string $driver_request = null;
    public ?string $additional_notes = null;
    public float $ojol_fee = 0;

    // Step 5: Konfirmasi & Promo
    public $promo_code = '';
    public $promo_discount = 0;
    public $promo_message = '';
    public $promo_error = '';
    public int $total_days = 1;
    public float $subtotal = 0;
    public float $driver_fee = 0;
    public int $operational_fee = 50000;
    public int $admin_fee = 10000;
    public float $tax_amount = 0;
    public float $grand_total = 0;

    // Post-Booking State
    public bool $is_finished = false;
    public ?string $final_booking_code = null;
    public ?string $wa_url = null;

    protected $DRIVER_FEE_PER_DAY = 150000;

    public function mount(\App\Services\CartService $cartService, ?string $car = null, bool $from_cart = false): void
    {
        if ($from_cart) {
            $cartItems = $cartService->get();
            if (empty($cartItems)) {
                $this->redirect(route('cars.index'));
                return;
            }
            $this->cars = Car::whereIn('id', array_keys($cartItems))->get();
        } elseif ($car) {
            $singleCar = Car::where('slug', $car)->first();
            if (!$singleCar) {
                $this->redirect(route('cars.index'));
                return;
            }
            $this->cars = collect([$singleCar]);
        } else {
            $this->redirect(route('cars.index'));
            return;
        }

        $this->car_names = $this->cars->pluck('car_name')->join(', ');

        // Pre-fill data dari user yang sudah login
        if (Auth::check()) {
            $user = Auth::user();
            $this->name  = $user->name;
            $this->email = $user->email;
            $this->phone      = $user->phone;
            $this->nik        = $user->nik;
            $this->sim_number = $user->sim_number;
            $this->address    = $user->address;
        }

        $this->pickup_date = now()->addDay()->format('Y-m-d');
        $this->return_date = now()->addDays(2)->format('Y-m-d');
        
        // Pre-select first store
        $firstBranch = Store::first();
        if ($firstBranch) {
            $this->branch_id = $firstBranch->id;
        }

        // Initial calculation
        $this->calculateTotal();
    }

    // ── Step Navigation ────────────────────────────────────────────────────────

    public function nextStep(): void
    {
        \Illuminate\Support\Facades\Log::info('nextStep triggered. Current step: ' . $this->step);

        if ($this->step === 1) {
            $this->validate([
                'pickup_date'      => 'required|date|after_or_equal:today',
                'return_date'      => 'required|date|after:pickup_date',
                'need_type'        => 'required|in:jemput,antar',
                'pickup_location'  => 'nullable|string|max:255',
                'rental_type'      => 'required|in:daily,monthly',
                'branch_id'        => 'required|exists:stores,id',
            ]);

            $unavailableCars = $this->getUnavailableCars();
            if ($unavailableCars->isNotEmpty()) {
                $names = $unavailableCars->pluck('car_name')->join(', ');
                session()->flash('error', "Sorry, the following vehicle(s) ({$names}) are already booked for your selected dates.");
                return;
            }

            $this->calculateTotal();
        }

        if ($this->step === 2) {
            $this->validate([
                'name'       => 'required|string|max:255',
                'email'      => 'required|email|max:255',
                'phone'      => 'required|string|max:20',
                'nik'        => 'required|string|max:50',
                'sim_number' => 'required|string|max:50',
                'address'    => 'required|string',
            ]);

            // Optional: Skip step 3 if already has docs and user is logged in
            if ($this->existingCustomerHasDocs()) {
                $this->step = 4; // Go straight to booking options
                return;
            }
        }

        if ($this->step === 3) {
            // Upload dokumen wajib jika belum punya atau belum login
            if (!$this->existingCustomerHasDocs()) {
                $this->validate([
                    'ktp_image' => 'required|image|max:2048',
                    'sim_image' => 'required|image|max:2048',
                    'kk_image'  => 'nullable|image|max:2048',
                    'npwp_image' => 'nullable|image|max:2048',
                    'pelajar_image' => 'nullable|image|max:2048',
                    'mahasiswa_image' => 'nullable|image|max:2048',
                ]);
            }
        }

        if ($this->step === 4) {
            $this->with_driver = (bool) $this->with_driver;
            $this->validate([
                'with_driver'      => 'required|boolean',
                'driver_request'   => 'nullable|string|max:100',
                'additional_notes' => 'nullable|string',
            ]);

            if ($this->with_driver) {
                $availableDriver = \App\Models\Driver::availableForDates($this->pickup_date, $this->return_date)->first();
                if (!$availableDriver) {
                    session()->flash('error', "Sorry, there are no drivers available for your selected dates.");
                    return;
                }
            }

            $this->calculateTotal();
        }

        $this->step++;
    }

    private function getUnavailableCars()
    {
        return $this->cars->filter(function ($car) {
            return Booking::where('car_id', $car->id)
                ->whereNotIn('booking_status', ['cancelled', 'expired'])
                ->where(function ($query) {
                    $query->whereBetween('pickup_date', [$this->pickup_date, $this->return_date])
                        ->orWhereBetween('return_date', [$this->pickup_date, $this->return_date])
                        ->orWhere(function ($q) {
                            $q->where('pickup_date', '<=', $this->pickup_date)
                                ->where('return_date', '>=', $this->return_date);
                        });
                })->exists();
        });
    }

    public function previousStep(): void
    {
        if ($this->step === 4 && $this->existingCustomerHasDocs()) {
            $this->step = 2;
            return;
        }
        $this->step--;
    }

    public function updated(string $propertyName): void
    {
        if (in_array($propertyName, ['pickup_date', 'return_date', 'with_driver', 'delivery_type', 'pickup_type', 'ojol_fee'])) {
            // Ensure boolean casting for with_driver
            if ($propertyName === 'with_driver') {
                $this->with_driver = (bool) $this->with_driver;
            }
            $this->calculateTotal();
        }
    }

    // ── Promo ──────────────────────────────────────────────────────────────────

    public function applyPromo(): void
    {
        $this->promo_message = '';
        $this->promo_error   = '';
        $this->promo_discount = 0;

        if (empty($this->promo_code)) {
            $this->promo_error = 'Please enter a promo code first.';
            return;
        }

        $promo = Promo::where('code', strtoupper($this->promo_code))
            ->where('status', true)
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString())
            ->first();

        if (!$promo) {
            $this->promo_error = 'Invalid or expired promo code.';
            return;
        }

        if ($promo->quota > 0 && $promo->used >= $promo->quota) {
            $this->promo_error = 'Promo quota has been reached.';
            return;
        }

        if ($this->grand_total < $promo->minimum_transaction) {
            $this->promo_error = 'Minimum transaction for this promo is Rp ' . number_format($promo->minimum_transaction, 0, ',', '.');
            return;
        }

        if ($promo->discount_type === 'percentage') {
            $this->promo_discount = ($this->grand_total * $promo->discount_value) / 100;
        } else {
            $this->promo_discount = $promo->discount_value;
        }

        $this->grand_total   = max(0, $this->grand_total - $this->promo_discount);
        $this->promo_message = 'Promo "' . $promo->title . '" applied successfully! Discount Rp ' . number_format($this->promo_discount, 0, ',', '.');
    }

    // ── Calculation ───────────────────────────────────────────────────────────

    public function calculateTotal(): void
    {
        $start = Carbon::parse($this->pickup_date);
        $end   = Carbon::parse($this->return_date);

        $this->total_days  = max(1, $start->diffInDays($end));
        
        $carPrices = 0;
        $driverPrices = 0;
        foreach ($this->cars as $car) {
            $carPrices += $car->daily_price * $this->total_days;
            if ($this->with_driver) {
                $driverPrices += $car->driver_daily_price * $this->total_days;
            }
        }

        $this->subtotal    = $carPrices;
        $this->driver_fee  = $driverPrices;
        
        // Calculate delivery fee
        if ($this->delivery_type === 'standard') {
            $this->delivery_fee = 100000;
        } elseif ($this->delivery_type === 'airport') {
            $this->delivery_fee = 200000;
        } else {
            $this->delivery_fee = 0;
        }

        // Calculate pickup fee
        if ($this->pickup_type === 'standard') {
            $this->pickup_fee = 100000;
        } elseif ($this->pickup_type === 'airport') {
            $this->pickup_fee = 200000;
        } else {
            $this->pickup_fee = 0;
        }

        // Parse ojol fee float safely
        $this->ojol_fee = floatval($this->ojol_fee);
        
        $base_total = $this->subtotal + $this->driver_fee + $this->delivery_fee + $this->pickup_fee + $this->ojol_fee + $this->operational_fee + $this->admin_fee;
        
        $this->tax_amount = $base_total * 0.12;
        $this->grand_total = $base_total + $this->tax_amount;

        // Reset diskon jika ada perubahan
        $this->promo_discount = 0;
        $this->promo_message  = '';
        $this->promo_code     = '';
    }

    // ── Submit ────────────────────────────────────────────────────────────────

    public function submit()
    {
        // 1. Cari atau buat User
        if (!Auth::check()) {
            // Cek user berdasarkan email
            $user = User::firstOrCreate(
                ['email' => $this->email],
                [
                    'name'     => $this->name,
                    'phone'    => $this->phone,
                    'password' => Hash::make($this->phone), // Default password = nomor HP
                    'status'   => 'active',
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('customer');
            Auth::login($user);
        } else {
            $user = Auth::user();
        }

        // 2. Update data terkini di user
        $user->update([
            'name'    => $this->name,
            'email'   => $this->email,
            'phone'   => $this->phone,
            'nik'     => $this->nik,
            'sim_number' => $this->sim_number,
            'address' => $this->address,
        ]);

        // Upload dokumen utama
        if ($this->ktp_image) {
            $user->ktp_image = $this->ktp_image->store('customers/ktp', 'public');
        }
        if ($this->sim_image) {
            $user->sim_image = $this->sim_image->store('customers/sim', 'public');
        }
        // Upload dokumen opsional
        if ($this->kk_image) {
            $user->kk_image = $this->kk_image->store('customers/kk', 'public');
        }
        if ($this->npwp_image) {
            $user->npwp_image = $this->npwp_image->store('customers/npwp', 'public');
        }
        if ($this->pelajar_image) {
            $user->pelajar_image = $this->pelajar_image->store('customers/pelajar', 'public');
        }
        if ($this->mahasiswa_image) {
            $user->mahasiswa_image = $this->mahasiswa_image->store('customers/mahasiswa', 'public');
        }
        $user->save();

        // 3. Ambil promo jika ada
        $promo    = null;
        $promoId  = null;
        $discount = $this->promo_discount;

        if (!empty($this->promo_code)) {
            $promo = Promo::where('code', strtoupper($this->promo_code))->first();
            if ($promo) {
                $promoId = $promo->id;
                $promo->increment('used');
            }
        }

        // 4. Buat Booking
        $bookingCode = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $finalNotes = $this->additional_notes;
        if ($this->with_driver && $this->driver_request) {
            $finalNotes = "Request Driver: " . $this->driver_request . "\n" . $this->additional_notes;
        }

        $booking = Booking::create([
            'booking_code'    => $bookingCode,
            'user_id'         => $user->id,
            'car_id'          => $this->cars->first()->id, // Primary car
            'store_id'        => $this->branch_id,
            'driver_id'       => $this->with_driver ? \App\Models\Driver::availableForDates($this->pickup_date, $this->return_date)->first()?->id : null,
            'promo_id'        => $promoId,
            'rental_type'     => $this->rental_type,
            'with_driver'     => $this->with_driver,
            'pickup_date'     => $this->pickup_date,
            'return_date'     => $this->return_date,
            'pickup_location' => $this->pickup_location,
            'return_location' => $this->return_location,
            'delivery_type'   => $this->delivery_type,
            'pickup_type'     => $this->pickup_type,
            'delivery_fee'    => $this->delivery_fee,
            'pickup_fee'      => $this->pickup_fee,
            'ojol_fee'        => $this->ojol_fee,
            'total_day'       => $this->total_days,
            'price'           => $this->subtotal / $this->total_days, // Average or total? let's use subtotal for now as total car price
            'driver_price'    => $this->driver_fee,
            'extra_price'     => $this->operational_fee + $this->admin_fee,
            'tax'             => $this->tax_amount,
            'discount'        => $discount,
            'grand_total'     => $this->grand_total,
            'payment_status'  => 'unpaid',
            'booking_status'  => 'pending',
            'notes'           => $finalNotes,
            'expired_at'      => now()->addHours(24),
            'guest_token'     => session()->getId() . '_' . Str::random(10),
            'guest_name'      => $this->name,
            'guest_email'     => $this->email,
            'guest_phone'     => $this->phone,
            'ktp_path'        => $user->ktp_image ?? null,
            'sim_path'        => $user->sim_image ?? null,
        ]);

        // (Booking items removed as consolidated to single car bookings)

        // Clear cart if booking from cart
        if (request()->has('from_cart') && request('from_cart') == 'true') {
            app(\App\Services\CartService::class)->clear();
        }

        // 5. Generate Midtrans Snap Token
        try {
            $midtrans = new \App\Services\MidtransService();
            $snapToken = $midtrans->getSnapToken($booking);

            \App\Models\Payment::create([
                'booking_id'     => $booking->id,
                'payment_code'   => 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'snap_token'     => $snapToken,
                'gross_amount'   => $booking->grand_total,
                'payment_status' => 'pending',
            ]);
        } catch (\Exception $e) {
            // Silently log error or handle it, but keep the booking
            \Illuminate\Support\Facades\Log::error('Midtrans Error: ' . $e->getMessage());
        }

        $this->dispatch('orderFinalized');

        // 6. WhatsApp Notification (Queued)
        try {
            $msg = "Hello *{$user->name}*,\n\nThank you for booking with *Siliwangi Rental*.\n\n" .
                   "Booking Code: *#{$booking->booking_code}*\n" .
                   "Vehicle: {$this->car_names}\n" .
                   "Total Amount: *Rp " . number_format($booking->grand_total, 0, ',', '.') . "*\n\n" .
                   "Please complete your payment via the link below:\n" .
                   route('invoice', $booking->booking_code) . "\n\n" .
                   "Thank you!";
            
            \App\Jobs\SendWhatsAppMessage::dispatch($user->phone, $msg);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WA Notification Queue Error: ' . $e->getMessage());
        }

        $this->final_booking_code = $booking->booking_code;
        $this->is_finished = true;

        if (!Auth::check()) {
            $adminWa = env('WA_ADMIN', '6281234567890');
            $text = urlencode("Hello Siliwangi Rental,\nI have just made a vehicle booking.\nBooking Code: *#{$booking->booking_code}*\nVehicle: {$this->car_names}\nTotal: Rp " . number_format($booking->grand_total, 0, ',', '.') . "\nInvoice Link: " . route('invoice', $booking->booking_code) . "\nPlease confirm my booking.");
            $this->wa_url = "https://wa.me/{$adminWa}?text={$text}";
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function existingCustomerHasDocs(): bool
    {
        if (!Auth::check()) return false;
        $user = Auth::user();
        return $user && $user->ktp_image && $user->sim_image;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.checkout', [
            'branches' => Store::orderBy('name')->get(),
        ]);
    }
}
