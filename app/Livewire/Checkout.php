<?php

namespace App\Livewire;

use App\Models\Store;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Customer;
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
    public ?string $booking_code = null;
    public bool $from_cart = false;

        // Step 1: Konfigurasi Sewa
    public ?string $pickup_date = null;
    public ?string $return_date = null;
    public string $need_type = 'jemput'; // jemput or antar
    public ?string $pickup_location = null;
    public ?string $return_location = null;
    public string $rental_type = 'daily'; // daily, monthly
    public string $rental_category = 'pribadi'; // pribadi, perusahaan
    public ?int $branch_id = null;
    public string $delivery_type = 'none'; // none, standard, airport
    public string $pickup_type = 'none'; // none, standard, airport
    public float $delivery_fee = 0;
    public float $pickup_fee = 0;
    public string $ojol_service = 'none'; // none, gojek, grab, maxim, lainnya

    // Step 2: Identitas
    public ?string $name = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $nik = null;
    public ?string $sim_number = null;
    public ?string $no_kk = null;
    public ?string $nip_nim = null;
    public ?string $pekerjaan = null;
    public ?string $address = null;

    // Step 3: Verifikasi Dokumen
    public mixed $selfie_image = null;
    public mixed $ktp_image = null;
    public mixed $sim_image = null;
    public mixed $kk_image = null;
    public mixed $id_card_image = null;

    // Step 4: Opsi Driver & Catatan
    public bool $with_driver = false; 
    public ?int $selected_driver_id = null;
    public ?string $driver_request = null;
    public ?string $additional_notes = null;
    public float $ojol_fee = 0;
    public bool $needs_ojol = false;

    // Step 4 tambahan: Lepas Kunci Destination
    public string $dest_type = 'jabotabek'; // 'jabotabek' or 'luar_jabotabek'
    public string $dest_region = 'jabar'; // 'jabar', 'jateng', 'diy', 'jatim', 'banten', 'bali'
    public ?string $dest_city = null;
    public float $dest_multiplier = 1.0;

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
        $this->from_cart = $from_cart || request()->query('from_cart') || request()->has('from_cart');

        if ($this->from_cart) {
            $cartItems = $cartService->get();
            if (empty($cartItems)) {
                $this->redirect(route('cars.index'));
                return;
            }
            $this->cars = Car::whereIn('id', array_keys($cartItems), 'and', false)->get();
        } elseif ($car) {
            $singleCar = Car::where('slug', '=', $car, 'and')->first(['*']);
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

        // Try restoring draft
        $restored = $this->restoreDraft();

        if (!$restored) {
            $this->booking_code = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            
            // Pre-fill data dari user yang sudah login
            if (Auth::check()) {
                $user = Auth::user();
                $this->name  = $user->name;
                $this->email = $user->email;
                $this->phone      = $user->phone;
                
                $customer = $user->customer;
                if ($customer) {
                    $this->nik        = $customer->nik;
                    $this->sim_number = $customer->sim_number;
                    $this->no_kk      = $customer->no_kk;
                    $this->nip_nim    = $customer->nip_nim;
                    $this->pekerjaan  = $customer->pekerjaan;
                    $this->address    = $customer->address;
                }
            }

            $this->pickup_date = now()->addDay()->format('Y-m-d');
            $this->return_date = now()->addDays(2)->format('Y-m-d');
            
            // Pre-select first store
            $firstBranch = Store::first(['*']);
            if ($firstBranch) {
                $this->branch_id = $firstBranch->id;
            }
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
                'rental_category'  => 'required|in:pribadi,perusahaan',
                'ojol_service'     => 'required|in:none,gojek,grab,maxim,lainnya',
                'branch_id'        => 'required|exists:stores,id',
            ]);

            $unavailableCars = $this->getUnavailableCars();
            if ($unavailableCars->isNotEmpty()) {
                $names = $unavailableCars->pluck('car_name')->join(', ');
                \Illuminate\Support\Facades\Session::flash('error', "Sorry, the following vehicle(s) ({$names}) are already booked for your selected dates.");
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
                'no_kk'      => 'required|string|max:50',
                'nip_nim'    => 'required|string|max:50',
                'pekerjaan'  => 'required|string|max:100',
                'address'    => 'required|string',
            ]);

            // Optional: Skip step 3 if already has docs and user is logged in
            if ($this->existingCustomerHasDocs()) {
                $this->step = 4; // Go straight to booking options
                $this->saveDraft();
                return;
            }
        }

        if ($this->step === 3) {
            // Upload dokumen wajib jika belum punya atau belum login
            if (!$this->existingCustomerHasDocs()) {
                $this->validate([
                    'selfie_image'  => 'required',
                    'ktp_image'     => 'required|image|max:5120',
                    'sim_image'     => 'required|image|max:5120',
                    'kk_image'      => 'required|image|max:5120',
                    'id_card_image' => 'required|image|max:5120',
                ]);
            }
        }

        if ($this->step === 4) {
            $this->with_driver = (bool) $this->with_driver;
            $this->needs_ojol  = (bool) $this->needs_ojol;
            
            // Rules dinamis untuk Step 4
            $rules = [
                'with_driver'      => 'required|boolean',
                'needs_ojol'       => 'required|boolean',
                'ojol_service'     => 'nullable|string',
                'ojol_fee'         => 'nullable|numeric|min:0',
                'additional_notes' => 'nullable|string',
            ];

            if ($this->with_driver) {
                $rules['selected_driver_id'] = 'nullable|exists:drivers,id';
            } else {
                $rules['dest_type'] = 'required|in:jabotabek,luar_jabotabek';
                if ($this->dest_type === 'luar_jabotabek') {
                    $rules['dest_region'] = 'required|in:jabar,jateng,diy,jatim,banten,bali';
                    $rules['dest_city']   = 'required|string|min:3|max:100';
                }
            }

            $this->validate($rules);

            // Validasi minimum hari sewa Lepas Kunci untuk daerah luar kota
            if (!$this->with_driver && $this->dest_type === 'luar_jabotabek') {
                $minDays = $this->getMinDaysForDestination();
                if ($this->total_days < $minDays) {
                    $regionName = match($this->dest_region) {
                        'jabar'   => 'Jawa Barat',
                        'jateng'  => 'Jawa Tengah',
                        'diy'     => 'D.I. Yogyakarta',
                        'jatim'   => 'Jawa Timur & Madura',
                        'banten'  => 'Banten',
                        'bali'    => 'Bali',
                        default   => 'Luar Jabotabek',
                    };
                    $cityName = $this->dest_city ? " ({$this->dest_city})" : "";
                    
                    \Illuminate\Support\Facades\Session::flash('error', "Untuk wilayah tujuan {$regionName}{$cityName}, minimum sewa lepas kunci adalah {$minDays} hari. Durasi sewa Anda saat ini adalah {$this->total_days} hari.");
                    return;
                }
            }

            // Jika memilih driver, simpan nama driver pilihan sebagai request
            if ($this->with_driver && $this->selected_driver_id) {
                $driver = \App\Models\Driver::where('id', '=', $this->selected_driver_id, 'and')->first();
                if ($driver) {
                    $this->driver_request = $driver->name;
                }
            }

            $this->calculateTotal();
        }

        $this->step++;
        $this->saveDraft();
    }

    public function getMinDaysForDestination(): int
    {
        if ($this->with_driver || $this->dest_type === 'jabotabek') {
            return 1;
        }

        $region = $this->dest_region;
        $city = strtolower(trim($this->dest_city ?? ''));

        switch ($region) {
            case 'banten':
                return 1;
            case 'jabar':
                if (str_contains($city, 'pangandaran') || str_contains($city, 'tasikmalaya') || str_contains($city, 'tasik')) {
                    return 2;
                }
                return 1;
            case 'jateng':
                return 3;
            case 'diy':
                return 3;
            case 'jatim':
                return 5;
            case 'bali':
                return 7;
            default:
                return 1;
        }
    }

    public function getDestinationMultiplier(): float
    {
        if ($this->with_driver || $this->dest_type === 'jabotabek') {
            return 1.0;
        }

        $region = $this->dest_region;
        $city = strtolower(trim($this->dest_city ?? ''));

        switch ($region) {
            case 'banten':
                return 1.0;
            case 'jabar':
                if (str_contains($city, 'pangandaran') || str_contains($city, 'tasikmalaya') || str_contains($city, 'tasik')) {
                    return 2.0;
                }
                return 1.5;
            case 'jateng':
                return 3.0;
            case 'diy':
                return 3.0;
            case 'jatim':
                return 5.0;
            case 'bali':
                return 7.0;
            default:
                return 1.0;
        }
    }

    private function getUnavailableCars()
    {
        return $this->cars->filter(function ($car) {
            return Booking::where('car_id', '=', $car->id, 'and')
                ->whereNotIn('booking_status', ['cancelled', 'expired'], 'and')
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
            $this->saveDraft();
            return;
        }
        $this->step--;
        $this->saveDraft();
    }

    public function updated(string $propertyName): void
    {
        if (in_array($propertyName, ['pickup_date', 'return_date', 'with_driver', 'delivery_type', 'pickup_type', 'ojol_fee', 'ojol_service', 'dest_type', 'dest_region', 'dest_city', 'needs_ojol'])) {
            // Ensure boolean casting for with_driver
            if ($propertyName === 'with_driver') {
                $this->with_driver = (bool) $this->with_driver;
            }
            if ($propertyName === 'needs_ojol') {
                $this->needs_ojol = (bool) $this->needs_ojol;
                if (!$this->needs_ojol) {
                    $this->ojol_service = 'none';
                    $this->ojol_fee = 0;
                }
            }
            $this->calculateTotal();
        }
        $this->saveDraft();
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

        $promo = Promo::where('code', '=', strtoupper($this->promo_code), 'and')
            ->where('status', '=', true, 'and')
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString())
            ->first(['*']);

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

        $this->dest_multiplier = $this->getDestinationMultiplier();
        $this->subtotal        = $carPrices * $this->dest_multiplier;
        $this->driver_fee      = $driverPrices;
        
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

    public function updatedOjolService(?string $value): void
    {
        if ($value === 'gojek') {
            $this->ojol_fee = 25000;
        } elseif ($value === 'grab') {
            $this->ojol_fee = 25000;
        } elseif ($value === 'maxim') {
            $this->ojol_fee = 20000;
        } elseif ($value === 'lainnya') {
            $this->ojol_fee = 25000;
        } else {
            $this->ojol_fee = 0;
        }
        $this->calculateTotal();
    }

    // ── Submit ────────────────────────────────────────────────────────────────

    public function submit()
    {
        // 1. Cari atau buat User (auth account)
        if (!Auth::check()) {
            $user = User::firstOrCreate(
                ['email' => $this->email],
                [
                    'name'     => $this->name,
                    'phone'    => $this->phone,
                    'password' => Hash::make($this->phone),
                    'status'   => 'active',
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('customer');
            Auth::login($user);
        } else {
            $user = Auth::user();
        }

        // Update data dasar user (kolom yang ada di tabel users)
        $user->update([
            'name'  => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        // 2. Cari atau buat Customer profile (tabel customers, FK: user_id)
        $customer = Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name'  => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
            ]
        );

        // Update data customer di tabel customers
        $customer->update([
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'nik'        => $this->nik,
            'sim_number' => $this->sim_number,
            'no_kk'      => $this->no_kk,
            'nip_nim'    => $this->nip_nim,
            'pekerjaan'  => $this->pekerjaan,
            'address'    => $this->address,
        ]);

        // Upload dokumen ke customers
        if ($this->selfie_image) {
            if (is_string($this->selfie_image) && str_starts_with($this->selfie_image, 'data:image')) {
                // Decode base64 and save
                $image_parts = explode(";base64,", $this->selfie_image);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'customers/selfie/' . uniqid() . '.' . $image_type;
                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image_base64);
                $customer->selfie_image = $fileName;
            } elseif ($this->selfie_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                // Regular uploaded file
                $customer->selfie_image = $this->selfie_image->store('customers/selfie', 'public');
            }
        }
        if ($this->ktp_image) {
            $customer->ktp_image = $this->ktp_image->store('customers/ktp', 'public');
            $customer->ktp_path  = $customer->ktp_image;
        }
        if ($this->sim_image) {
            $customer->sim_image = $this->sim_image->store('customers/sim', 'public');
            $customer->sim_path  = $customer->sim_image;
        }
        if ($this->kk_image) {
            $customer->kk_photo = $this->kk_image->store('customers/kk', 'public');
        }
        if ($this->id_card_image) {
            $customer->id_card_photo = $this->id_card_image->store('customers/id_card', 'public');
        }
        $customer->save();

        // 3. Ambil promo jika ada
        $promo    = null;
        $promoId  = null;
        $discount = $this->promo_discount;

        if (!empty($this->promo_code)) {
            $promo = Promo::where('code', '=', strtoupper($this->promo_code), 'and')->first(['*']);
            if ($promo) {
                $promoId = $promo->id;
                $promo->increment('used', 1);
            }
        }

        // 4. Buat Booking
        $bookingCode = $this->booking_code ?: 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $finalNotes = $this->additional_notes;
        if (!$this->with_driver) {
            $destText = "Tujuan: " . ($this->dest_type === 'jabotabek' ? 'Dalam JABODETABEK' : 'Luar JABODETABEK');
            if ($this->dest_type === 'luar_jabotabek') {
                $regionName = match($this->dest_region) {
                    'jabar'   => 'Jawa Barat',
                    'jateng'  => 'Jawa Tengah',
                    'diy'     => 'D.I. Yogyakarta',
                    'jatim'   => 'Jawa Timur & Madura',
                    'banten'  => 'Banten',
                    'bali'    => 'Bali',
                    default   => 'Luar Jabotabek',
                };
                $destText .= " ({$regionName} - {$this->dest_city})";
            }
            $finalNotes = $destText . "\n" . $finalNotes;
        }
        if ($this->with_driver && $this->driver_request) {
            $finalNotes = "Request Driver: " . $this->driver_request . "\n" . $finalNotes;
        }
        if ($this->ojol_service !== 'none') {
            $finalNotes = "Layanan Ojol: " . strtoupper($this->ojol_service) . "\n" . $finalNotes;
        }

        $booking = Booking::create([
            'booking_code'    => $bookingCode,
            'customer_id'     => $customer->id,
            'car_id'          => $this->cars->first()->id, // Primary car
            'store_id'        => $this->branch_id,
            'driver_id'       => $this->with_driver ? ($this->selected_driver_id ?? \App\Models\Driver::availableForDates($this->pickup_date, $this->return_date)->first()?->id) : null,
            'driver_name'     => $this->with_driver ? ($this->driver_request ?? \App\Models\Driver::where('id', '=', $this->selected_driver_id ?? 0, 'and')->value('name')) : null,
            'promo_id'        => $promoId,
            'rental_type'     => $this->rental_type,
            'rental_category' => $this->rental_category,
            'area'            => !$this->with_driver ? ($this->dest_type === 'jabotabek' ? 'jabodetabek' : 'luar_jabodetabek') : null,
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
            'guest_token'     => \Illuminate\Support\Facades\Session::getId() . '_' . Str::random(10),
            'guest_name'      => $this->name,
            'guest_email'     => $this->email,
            'guest_phone'     => $this->phone,
            'ktp_path'        => $customer->ktp_path ?? null,
            'sim_path'        => $customer->sim_path ?? null,
        ]);

        // (Booking items removed as consolidated to single car bookings)

        // Clear cart if booking from cart
        if ($this->from_cart) {
            app(\App\Services\CartService::class)->clear();
        }

        // 5. Generate Midtrans Snap Token
        try {
            $midtrans = app(\App\Services\MidtransService::class);
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
            $msg = "Hello *{$this->name}*,\n\nThank you for booking with *Siliwangi Rental*.\n\n" .
                "Booking Code: *#{$booking->booking_code}*\n" .
                "Vehicle: {$this->car_names}\n" .
                "Total Amount: *Rp " . number_format($booking->grand_total, 0, ',', '.') . "*\n\n" .
                "Please complete your payment via the link below:\n" .
                route('invoice', $booking->booking_code) . "\n\n" .
                "Thank you!";
    
            \App\Jobs\SendWhatsAppMessage::dispatch($this->phone, $msg);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WA Notification Queue Error: ' . $e->getMessage());
        }

        $this->final_booking_code = $booking->booking_code;
        $this->is_finished = true;

        if (!Auth::check()) {
            $adminWa = config('services.fonnte.admin_phone', '628973816530');
            $text = urlencode("Hello Siliwangi Rental,\nI have just made a vehicle booking.\nBooking Code: *#{$booking->booking_code}*\nVehicle: {$this->car_names}\nTotal: Rp " . number_format($booking->grand_total, 0, ',', '.') . "\nInvoice Link: " . route('invoice', $booking->booking_code) . "\nPlease confirm my booking.");
            $this->wa_url = "https://wa.me/{$adminWa}?text={$text}";
        }

        // Delete checkout draft upon successful submission
        \App\Models\CheckoutDraft::where('user_id', '=', Auth::id(), 'and')
            ->orWhere('ip_address', '=', request()->ip())
            ->delete();
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function existingCustomerHasDocs(): bool
    {
        if (!Auth::check()) return false;
        $customer = Customer::where('user_id', '=', Auth::id(), 'and')->first(['*']);
        return $customer && $customer->ktp_image && $customer->sim_image && $customer->selfie_image && $customer->kk_photo && $customer->id_card_photo;
    }

    public function saveDraft(): void
    {
        if ($this->is_finished) {
            return;
        }

        $payload = [
            'booking_code'       => $this->booking_code,
            'pickup_date'        => $this->pickup_date,
            'return_date'        => $this->return_date,
            'need_type'          => $this->need_type,
            'pickup_location'    => $this->pickup_location,
            'return_location'    => $this->return_location,
            'rental_type'        => $this->rental_type,
            'rental_category'    => $this->rental_category,
            'branch_id'          => $this->branch_id,
            'delivery_type'      => $this->delivery_type,
            'pickup_type'        => $this->pickup_type,
            'ojol_service'       => $this->ojol_service,
            'name'               => $this->name,
            'email'              => $this->email,
            'phone'              => $this->phone,
            'nik'                => $this->nik,
            'sim_number'         => $this->sim_number,
            'no_kk'              => $this->no_kk,
            'nip_nim'            => $this->nip_nim,
            'pekerjaan'          => $this->pekerjaan,
            'address'            => $this->address,
            'with_driver'        => $this->with_driver,
            'selected_driver_id' => $this->selected_driver_id,
            'driver_request'     => $this->driver_request,
            'additional_notes'   => $this->additional_notes,
            'ojol_fee'           => $this->ojol_fee,
            'needs_ojol'         => $this->needs_ojol,
            'dest_type'          => $this->dest_type,
            'dest_region'        => $this->dest_region,
            'dest_city'          => $this->dest_city,
        ];

        \App\Models\CheckoutDraft::updateOrCreate(
            [
                'user_id'    => Auth::id(),
                'ip_address' => request()->ip(),
            ],
            [
                'step'    => $this->step,
                'payload' => $payload,
            ]
        );
    }

    public function restoreDraft(): bool
    {
        $draft = null;

        if (Auth::check()) {
            $draft = \App\Models\CheckoutDraft::where('user_id', '=', Auth::id(), 'and')->first(['*']);
        }

        if (!$draft) {
            $draft = \App\Models\CheckoutDraft::where('ip_address', '=', request()->ip(), 'and')
                ->whereNull('user_id', 'and', false)
                ->first(['*']);
        }

        if ($draft && $draft->payload) {
            $payload = $draft->payload;
            $this->step = $draft->step;

            foreach ($payload as $key => $value) {
                if ($key && is_string($key) && !str_starts_with($key, '$') && $key !== '$' && property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }

            $this->with_driver = (bool) $this->with_driver;
            $this->needs_ojol  = (bool) $this->needs_ojol;

            session()->flash('info', 'Formulir Anda sebelumnya telah dipulihkan secara otomatis.');
            return true;
        }

        return false;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.checkout', [
            'branches' => Store::orderBy('name', 'asc')->get(),
        ]);
    }
}
