<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Car;
use App\Models\Booking;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CarDetail extends Component
{
    use WithFileUploads;

    public ?\App\Models\Car $car = null;
    
    // Booking Form
    public ?string $pickup_date = null;
    public ?string $return_date = null;
    public bool $with_driver = false;
    
    // Customer Form
    public ?string $name = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $identity_number = null;
    public mixed $identity_photo = null;

    // Pricing
    public int $driver_daily_fee = 150000;

    protected $rules = [
        'pickup_date' => 'required|date|after_or_equal:today',
        'return_date' => 'required|date|after_or_equal:pickup_date',
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'identity_number' => 'required|string|max:50',
        'identity_photo' => 'required|image|max:2048', // Max 2MB
    ];

    public function mount(string $slug)
    {
        $this->car = \Illuminate\Support\Facades\Cache::remember("car_detail_{$slug}", 3600, function() use ($slug) {
            return Car::where('slug', '=', $slug, 'and')->firstOrFail();
        });
        $this->pickup_date = now()->format('Y-m-d');
        $this->return_date = now()->addDays(2)->format('Y-m-d');

        // Pre-fill if logged in
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->identity_number = $user->nik;
        }
    }

    public function getSummaryProperty()
    {
        $pickup = Carbon::parse($this->pickup_date);
        $return = Carbon::parse($this->return_date);
        
        // Include return date as full day
        $total_days = $pickup->diffInDays($return) + 1;
        if ($total_days < 1) $total_days = 1;

        $car_total = $this->car->daily_price * $total_days;
        $driver_total = $this->with_driver ? ($this->driver_daily_fee * $total_days) : 0;
        $grand_total = $car_total + $driver_total;

        return [
            'total_days' => $total_days,
            'car_total' => $car_total,
            'driver_total' => $driver_total,
            'grand_total' => $grand_total,
        ];
    }

    public function setDriver(bool $status)
    {
        $this->with_driver = $status;
    }

    public function book()
    {
        $this->validate();

        // 1. Upload Photo
        $photoPath = $this->identity_photo->store('identities', 'public');

        // 2. Manage User / Customer
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            // Find or create User by email
            $user = \App\Models\User::firstOrCreate(
                ['email' => $this->email],
                [
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'password' => \Illuminate\Support\Facades\Hash::make($this->phone),
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('customer');
            Auth::login($user);
        }

        // Update user profile and documents directly
        $user->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'nik' => $this->identity_number,
            'ktp_image' => $photoPath,
        ]);

        // 3. Create Booking
        $summary = $this->getSummaryProperty();
        
        // Generate random booking code
        $bookingCode = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'user_id' => $user->id,
            'car_id' => $this->car->id,
            'store_id' => $this->car->store_id,
            'rental_type' => 'daily',
            'pickup_date' => $this->pickup_date,
            'return_date' => $this->return_date,
            'total_day' => $summary['total_days'],
            'price' => $this->car->daily_price,
            'driver_price' => $summary['driver_total'], // store total driver price
            'grand_total' => $summary['grand_total'],
            'payment_status' => 'unpaid',
            'booking_status' => 'pending',
            'expired_at' => now()->addHours(24), // Pay within 24 hours
            'guest_token' => \Illuminate\Support\Facades\Session::getId() . '_' . Str::random(10),
            'guest_name' => $this->name,
            'guest_email' => $this->email,
            'guest_phone' => $this->phone,
            'ktp_path' => $photoPath,
        ]);

        return redirect()->route('checkout.success', ['code' => $booking->booking_code]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.car-detail');
    }
}
