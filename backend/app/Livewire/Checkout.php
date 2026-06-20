<?php

namespace App\Livewire;

use App\Models\Store;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Promo;
use App\Models\User;
use App\Services\ImageUploadService;
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
    public bool $is_corporate_only = false;
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
                
                $this->nik        = $user->nik;
                $this->sim_number = $user->sim_number;
                $this->no_kk      = $user->no_kk;
                $this->nip_nim    = $user->nip_nim;
                $this->pekerjaan  = $user->pekerjaan;
                $this->address    = $user->address;
            }

            $this->pickup_date = now()->addDay()->format('Y-m-d');
            $this->return_date = now()->addDays(2)->format('Y-m-d');
            
            // Pre-select first store
            $firstBranch = Store::first(['*']);
            if ($firstBranch) {
                $this->branch_id = $firstBranch->id;
            }
        }

        // Pre-select dest_city if empty
        if (empty($this->dest_city)) {
            $cities = $this->getCitiesForRegion($this->dest_region);
            $this->dest_city = !empty($cities) ? $cities[0] : null;
        }

        $anyCallForPrice = collect($this->cars)->contains('is_call_for_price', true);
        $this->is_corporate_only = $anyCallForPrice;
        if ($this->is_corporate_only) {
            $this->rental_category = 'perusahaan';
        } elseif (empty($this->rental_category) || ($this->rental_category === 'perusahaan' && !$restored)) {
            $this->rental_category = 'pribadi';
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
                'rental_category'  => $this->is_corporate_only ? 'required|in:perusahaan' : 'required|in:pribadi,perusahaan',
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
                $rules = [
                    'ktp_image'     => 'required|image|max:2048|mimes:jpeg,jpg,png,webp,gif',
                    'sim_image'     => 'required|image|max:2048|mimes:jpeg,jpg,png,webp,gif',
                    'kk_image'      => 'required|image|max:2048|mimes:jpeg,jpg,png,webp,gif',
                    'id_card_image' => 'required|image|max:2048|mimes:jpeg,jpg,png,webp,gif',
                ];

                if ($this->selfie_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    $rules['selfie_image'] = 'required|image|max:2048';
                } else {
                    $rules['selfie_image'] = 'required';
                    if (is_string($this->selfie_image) && str_starts_with($this->selfie_image, 'data:image')) {
                        $base64Size = strlen($this->selfie_image) * 3 / 4;
                        if ($base64Size > 2 * 1024 * 1024) {
                            $this->addError('selfie_image', 'Foto selfie tidak boleh lebih besar dari 2 megabita.');
                            return;
                        }
                    }
                }

                $this->validate($rules);
            } else {
                $rules = [];
                if ($this->ktp_image) {
                    $rules['ktp_image'] = 'image|max:2048|mimes:jpeg,jpg,png,webp,gif';
                }
                if ($this->sim_image) {
                    $rules['sim_image'] = 'image|max:2048|mimes:jpeg,jpg,png,webp,gif';
                }
                if ($this->kk_image) {
                    $rules['kk_image'] = 'image|max:2048|mimes:jpeg,jpg,png,webp,gif';
                }
                if ($this->id_card_image) {
                    $rules['id_card_image'] = 'image|max:2048|mimes:jpeg,jpg,png,webp,gif';
                }
                if ($this->selfie_image) {
                    if ($this->selfie_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                        $rules['selfie_image'] = 'image|max:2048';
                    } elseif (is_string($this->selfie_image) && str_starts_with($this->selfie_image, 'data:image')) {
                        $base64Size = strlen($this->selfie_image) * 3 / 4;
                        if ($base64Size > 2 * 1024 * 1024) {
                            $this->addError('selfie_image', 'Foto selfie tidak boleh lebih besar dari 2 megabita.');
                            return;
                        }
                    }
                }
                if (!empty($rules)) {
                    $this->validate($rules);
                }
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
                    $rules['dest_region'] = 'required|in:jabar,jateng,diy,jatim,banten,bali,sumatera';
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
                        'sumatera' => 'Sumatera',
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

        if ($this->step < 5) {
            $this->step++;
        }
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
                return 2;
            case 'diy':
                return 3;
            case 'jatim':
                return 5;
            case 'bali':
                return 7;
            case 'sumatera':
                return 1;
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
            case 'sumatera':
                return 1.0;
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
            // Reset dest_city if region changes
            if ($propertyName === 'dest_region') {
                $cities = $this->getCitiesForRegion($this->dest_region);
                $this->dest_city = !empty($cities) ? $cities[0] : null;
            }
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

        // Update data dasar user dan profil terpadu
        $user->update([
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

        // Upload dokumen — semua gambar dikonversi ke WebP sebelum disimpan
        /** @var ImageUploadService $imageService */
        $imageService = app(ImageUploadService::class);

        if ($this->selfie_image) {
            if (is_string($this->selfie_image) && str_starts_with($this->selfie_image, 'data:image')) {
                // Kamera browser: base64 → WebP
                try {
                    $user->avatar = $imageService->storeBase64AsWebp($this->selfie_image, 'users/selfie');
                } catch (\RuntimeException $e) {
                    $this->addError('selfie_image', $e->getMessage());
                    return;
                }
            } elseif ($this->selfie_image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                // File upload biasa → WebP
                try {
                    $imageService->deleteOldFile($user->avatar);
                    $user->avatar = $imageService->storeAsWebp($this->selfie_image, 'users/selfie');
                } catch (\RuntimeException $e) {
                    $this->addError('selfie_image', $e->getMessage());
                    return;
                }
            }
        }
        if ($this->ktp_image) {
            try {
                $imageService->deleteOldFile($user->ktp_image);
                $user->ktp_image = $imageService->storeAsWebp($this->ktp_image, 'users/ktp');
            } catch (\RuntimeException $e) {
                $this->addError('ktp_image', $e->getMessage());
                return;
            }
        }
        if ($this->sim_image) {
            try {
                $imageService->deleteOldFile($user->sim_image);
                $user->sim_image = $imageService->storeAsWebp($this->sim_image, 'users/sim');
            } catch (\RuntimeException $e) {
                $this->addError('sim_image', $e->getMessage());
                return;
            }
        }
        if ($this->kk_image) {
            try {
                $imageService->deleteOldFile($user->kk_image);
                $user->kk_image = $imageService->storeAsWebp($this->kk_image, 'users/kk');
            } catch (\RuntimeException $e) {
                $this->addError('kk_image', $e->getMessage());
                return;
            }
        }
        if ($this->id_card_image) {
            try {
                $imageService->deleteOldFile($user->pelajar_image);
                $user->pelajar_image = $imageService->storeAsWebp($this->id_card_image, 'users/id_card');
            } catch (\RuntimeException $e) {
                $this->addError('id_card_image', $e->getMessage());
                return;
            }
        }
        $user->save();

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
                    'sumatera' => 'Sumatera',
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
            'user_id'         => $user->id,
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
            'ktp_path'        => $user->ktp_image ?? null,
            'sim_path'        => $user->sim_image ?? null,
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
        session()->forget('checkout_draft');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function existingCustomerHasDocs(): bool
    {
        if (!Auth::check()) return false;
        $user = Auth::user();
        return $user && $user->ktp_image && $user->sim_image && $user->avatar && $user->kk_image && $user->pelajar_image;
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

        session()->put('checkout_draft', [
            'step'    => $this->step,
            'payload' => $payload,
        ]);
    }

    public function restoreDraft(): bool
    {
        $draft = session()->get('checkout_draft');

        if ($draft && isset($draft['payload'])) {
            $payload = $draft['payload'];
            $this->step = min(5, max(1, $draft['step'] ?? 1));

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

    public function getCitiesForRegion(string $region): array
    {
        $cities_by_region = [
            'jabar' => [
                'Kabupaten Bandung',
                'Kabupaten Bandung Barat',
                'Kabupaten Bekasi',
                'Kabupaten Bogor',
                'Kabupaten Ciamis',
                'Kabupaten Cianjur',
                'Kabupaten Cirebon',
                'Kabupaten Garut',
                'Kabupaten Indramayu',
                'Kabupaten Karawang',
                'Kabupaten Kuningan',
                'Kabupaten Majalengka',
                'Kabupaten Pangandaran',
                'Kabupaten Purwakarta',
                'Kabupaten Subang',
                'Kabupaten Sukabumi',
                'Kabupaten Sumedang',
                'Kabupaten Tasikmalaya',
                'Kota Bandung',
                'Kota Banjar',
                'Kota Bekasi',
                'Kota Bogor',
                'Kota Cimahi',
                'Kota Cirebon',
                'Kota Depok',
                'Kota Sukabumi',
                'Kota Tasikmalaya',
            ],
            'jateng' => [
                'Kota Semarang',
                'Kota Surakarta (Solo)',
                'Kota Magelang',
                'Kota Salatiga',
                'Kota Pekalongan',
                'Kota Tegal',
                'Kabupaten Banjarnegara',
                'Kabupaten Banyumas',
                'Kabupaten Batang',
                'Kabupaten Blora',
                'Kabupaten Boyolali',
                'Kabupaten Brebes',
                'Kabupaten Cilacap',
                'Kabupaten Demak',
                'Kabupaten Grobogan',
                'Kabupaten Jepara',
                'Kabupaten Karanganyar',
                'Kabupaten Kebumen',
                'Kabupaten Kendal',
                'Kabupaten Klaten',
                'Kabupaten Kudus',
                'Kabupaten Magelang',
                'Kabupaten Pati',
                'Kabupaten Pekalongan',
                'Kabupaten Pemalang',
                'Kabupaten Purbalingga',
                'Kabupaten Purworejo',
                'Kabupaten Rembang',
                'Kabupaten Semarang',
                'Kabupaten Sragen',
                'Kabupaten Sukoharjo',
                'Kabupaten Tegal',
                'Kabupaten Temanggung',
                'Kabupaten Wonogiri',
                'Kabupaten Wonosobo',
            ],
            'jatim' => [
                'Kabupaten Bangkalan',
                'Kabupaten Banyuwangi',
                'Kabupaten Blitar',
                'Kabupaten Bojonegoro',
                'Kabupaten Bondowoso',
                'Kabupaten Gresik',
                'Kabupaten Jember',
                'Kabupaten Jombang',
                'Kabupaten Kediri',
                'Kabupaten Lamongan',
                'Kabupaten Lumajang',
                'Kabupaten Madiun',
                'Kabupaten Magetan',
                'Kabupaten Malang',
                'Kabupaten Mojokerto',
                'Kabupaten Nganjuk',
                'Kabupaten Ngawi',
                'Kabupaten Pacitan',
                'Kabupaten Pamekasan',
                'Kabupaten Pasuruan',
                'Kabupaten Ponorogo',
                'Kabupaten Probolinggo',
                'Kabupaten Sampang',
                'Kabupaten Sidoarjo',
                'Kabupaten Situbondo',
                'Kabupaten Sumenep',
                'Kabupaten Trenggalek',
                'Kabupaten Tuban',
                'Kabupaten Tulungagung',
                'Kota Batu',
                'Kota Blitar',
                'Kota Kediri',
                'Kota Madiun',
                'Kota Malang',
                'Kota Mojokerto',
                'Kota Pasuruan',
                'Kota Probolinggo',
                'Kota Surabaya',
            ],
            'banten' => [
                'Kabupaten Pandeglang',
                'Kabupaten Lebak',
                'Kabupaten Serang',
                'Kabupaten Tangerang',
                'Kota Serang',
                'Kota Cilegon',
                'Kota Tangerang',
                'Kota Tangerang Selatan',
            ],
            'diy' => [
                'Belum ditentukan pada requirement.',
            ],
            'bali' => [
                'Belum ditentukan pada requirement.',
            ],
            'sumatera' => [
                'Banda Aceh (Aceh)',
                'Lhokseumawe (Aceh)',
                'Sabang (Aceh)',
                'Langsa (Aceh)',
                'Meulaboh (Aceh)',
                'Medan (Sumatera Utara)',
                'Binjai (Sumatera Utara)',
                'Pematangsiantar (Sumatera Utara)',
                'Tebing Tinggi (Sumatera Utara)',
                'Tanjungbalai (Sumatera Utara)',
                'Padang (Sumatera Barat)',
                'Bukittinggi (Sumatera Barat)',
                'Payakumbuh (Sumatera Barat)',
                'Solok (Sumatera Barat)',
                'Pariaman (Sumatera Barat)',
                'Pekanbaru (Riau)',
                'Dumai (Riau)',
                'Bengkalis (Riau)',
                'Siak (Riau)',
                'Rokan Hilir (Riau)',
                'Tanjung Pinang (Kepulauan Riau)',
                'Batam (Kepulauan Riau)',
                'Karimun (Kepulauan Riau)',
                'Natuna (Kepulauan Riau)',
                'Lingga (Kepulauan Riau)',
                'Kota Jambi (Jambi)',
                'Sungai Penuh (Jambi)',
                'Muaro Jambi (Jambi)',
                'Sarolangun (Jambi)',
                'Palembang (Sumatera Selatan)',
                'Lubuklinggau (Sumatera Selatan)',
                'Prabumulih (Sumatera Selatan)',
                'Pagar Alam (Sumatera Selatan)',
                'Pangkalpinang (Bangka Belitung)',
                'Belitung (Bangka Belitung)',
                'Bangka Barat (Bangka Belitung)',
                'Bangka Tengah (Bangka Belitung)',
                'Kota Bengkulu (Bengkulu)',
                'Rejang Lebong (Bengkulu)',
                'Mukomuko (Bengkulu)',
                'Seluma (Bengkulu)',
                'Bandar Lampung (Lampung)',
                'Metro (Lampung)',
                'Pringsewu (Lampung)',
                'Lampung Tengah (Lampung)',
                'Lampung Timur (Lampung)',
            ],
        ];

        return $cities_by_region[$region] ?? [];
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.checkout', [
            'branches' => Store::orderBy('name', 'asc')->get(),
        ]);
    }
}
