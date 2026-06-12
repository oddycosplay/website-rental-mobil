<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })
            ->withCount('bookings')
            ->latest()
            ->paginate(15);

        // Add total transaction amount for each customer
        foreach ($customers as $customer) {
            $customer->total_transaction = Payment::query()->whereIn('booking_id', $customer->bookings->pluck('id'))
                ->where('payment_status', 'success')
                ->sum('paid_amount');
        }

        $stats = [
            'total_customers' => User::whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })->count(),
            'active_customers' => User::whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })->whereHas('bookings', function($q) {
                $q->where('bookings.created_at', '>=', now()->startOfMonth());
            })->count(),
            'new_customers' => User::whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })->where('users.created_at', '>=', now()->subDays(30))->count(),
            'blacklist_customers' => User::whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })->where('status', 'blacklist')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(User $customer)
    {
        $customer->load(['bookings.car', 'bookings.payments']);
        
        $customer->total_rental = $customer->bookings->count();
        $customer->total_transaction = Payment::query()->whereIn('booking_id', $customer->bookings->pluck('id'))
            ->where('payment_status', 'success')
            ->sum('paid_amount');

        $customer->recent_bookings = $customer->bookings()
            ->with('car')
            ->latest()
            ->take(5)
            ->get();

        return response()->json($customer);
    }
}
