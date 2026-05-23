<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Booking;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $unpaidBookings = collect();
            if (auth()->check()) {
                if (auth()->user()->hasRole('customer')) {
                    $unpaidBookings = Booking::query()->where('user_id', auth()->id())
                        ->where('payment_status', 'unpaid')
                        ->with('car')
                        ->get();
                } else {
                    $unpaidBookings = Booking::query()->where('payment_status', 'unpaid')
                        ->with('car')
                        ->get();
                }
            }
            $view->with('unpaidBookings', $unpaidBookings);
        });

        // Explicitly set Livewire routes with subdirectory prefix only when requested under Laragon subdirectory
        if (str_contains(request()->getRequestUri(), '/rental_project/public') || str_contains(request()->getBaseUrl(), '/rental_project')) {
            \Livewire\Livewire::setUpdateRoute(function ($handle) {
                return Route::post('/rental_project/public/livewire/update', $handle);
            });

            \Livewire\Livewire::setScriptRoute(function ($handle) {
                return Route::get('/rental_project/public/livewire/livewire.js', $handle);
            });
        }

        $this->configureRateLimiting();
    }
    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(1000)->by($request->ip());
        });
    }
}
